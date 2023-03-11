<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Throwable;
use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;


use Akaunting\Apexcharts\Chart;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Order;
use App\Models\OutgoingStock;


class StaffDashboardController extends Controller
{
    //all
    public function staffDashboard($start_date="", $end_data="")
    {
        //return $authUser = auth()->user()->role(auth()->user()->id)->role->permissions->contains('slug', 'view-product-list');
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'all';
        ///////////////////////////////////////////////////////////////////////
        
        $expenses = Expense::where('staff_id', $authUser->id)->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders->count();
        $newOrders = $authUser->assignedOrders()->where('status', 'new')->count();
        $pendingOrders = $authUser->assignedOrders()->where('status', 'pending')->count();
        $remittedOrders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->count();
        $notRemittedOrders = $authUser->assignedOrders()->where('status', 'delivered_not_remitted')->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid); //total revenue

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboard', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //by date
    public function staffDashboardFilterPost(Request $request)
    {
        $request->validate([
            'end_date' => 'required|date',
            'start_date' => 'required|date|before:end_date',
        ]);

        $data = $request->all();

        return redirect()->route('staffDashboardDateFilter', [$data['start_date'], $data['end_date']]);
    }

    public function staffDashboardDateFilter($start_date="", $end_date="", $duration="")
    {
        //return $authUser = auth()->user()->role(auth()->user()->id)->role->permissions->contains('slug', 'view-product-list');
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'all';
        ///////////////////////////////////////////////////////////////////////

        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');
        //  = date('Y-m-d', (int) $start_date);
        // $end_date = date('Y-m-d', (int) $end_date);

        $expenses = Expense::where('staff_id', $authUser->id)->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->count();
        $newOrders = $authUser->assignedOrders()->where('status', 'new')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->count();
        $pendingOrders = $authUser->assignedOrders()->where('status', 'pending')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->count();
        $remittedOrders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->count();
        $notRemittedOrders = $authUser->assignedOrders()->where('status', 'delivered_not_remitted')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //for today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //for tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report, chart
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDateFilter', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount', 'start_date', 'end_date'));
    }

    //today
    public function staffTodayRecord()
    {
        //return $authUser = auth()->user()->role(auth()->user()->id)->role->permissions->contains('slug', 'view-product-list');
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'today';
        ///////////////////////////////////////////////////////////////////////

        $dt = Carbon::now();
        
        $expenses = Expense::where('staff_id', $authUser->id)->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->count();
        $newOrders = $authUser->assignedOrders()->where('status', 'new')->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->count();
        $pendingOrders = $authUser->assignedOrders()->where('status', 'pending')->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->count();
        $remittedOrders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->count();
        $notRemittedOrders = $authUser->assignedOrders()->where('status', 'delivered_not_remitted')->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //yesterday
    public function staffYesterdayRecord()
    {
        //return $authUser = auth()->user()->role(auth()->user()->id)->role->permissions->contains('slug', 'view-product-list');
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'yesterday';
        ///////////////////////////////////////////////////////////////////////

        $yesterday = Carbon::yesterday();
        //$yesterday = $dt->copy()->subDays(1);
        //Post::whereDate('created_at', Carbon::yesterday())->get();
        
        $expenses = Expense::where('staff_id', $authUser->id)->whereDate('created_at', $yesterday)->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->whereDate('created_at', $yesterday)->count();
        $newOrders = $authUser->assignedOrders()->where('status', 'new')->whereDate('created_at', $yesterday)->count();
        $pendingOrders = $authUser->assignedOrders()->where('status', 'pending')->whereDate('created_at', $yesterday)->count();
        $remittedOrders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->whereDate('created_at', $yesterday)->count();
        $notRemittedOrders = $authUser->assignedOrders()->where('status', 'delivered_not_remitted')->whereDate('created_at', $yesterday)->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->whereDate('created_at', $yesterday)->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //last 7 days
    public function staffLast7DaysRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'last 7 days';
        ///////////////////////////////////////////////////////////////////////

        //$last7days = Carbon::now()->subDays(7);
        $last7days = Carbon::today()->subDays(7);
        //$users = User::where('created_at','>=',$date)->get();
        
        $expenses = Expense::where('staff_id', $authUser->id)->where('created_at', '>=', $last7days)->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->where('created_at', $last7days)->count();
        $newOrders = $authUser->assignedOrders()->where('status', 'new')->where('created_at', '>=', $last7days)->count();
        $pendingOrders = $authUser->assignedOrders()->where('status', 'pending')->where('created_at', '>=', $last7days)->count();
        $remittedOrders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->where('created_at', '>=', $last7days)->count();
        $notRemittedOrders = $authUser->assignedOrders()->where('status', 'delivered_not_remitted')->where('created_at', '>=', $last7days)->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->where('created_at', '>=', $last7days)->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //last 14 days
    public function staffLast14DaysRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'last 14 days';
        ///////////////////////////////////////////////////////////////////////

        //$last7days = Carbon::now()->subDays(7);
        $last14days = Carbon::today()->subDays(14);
        //$users = User::where('created_at','>=',$date)->get();
        
        $expenses = Expense::where('staff_id', $authUser->id)->where('created_at', '>=', $last14days)->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->where('created_at', $last14days)->count();
        $newOrders = $authUser->assignedOrders()->where('status', 'new')->where('created_at', '>=', $last14days)->count();
        $pendingOrders = $authUser->assignedOrders()->where('status', 'pending')->where('created_at', '>=', $last14days)->count();
        $remittedOrders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->where('created_at', '>=', $last14days)->count();
        $notRemittedOrders = $authUser->assignedOrders()->where('status', 'delivered_not_remitted')->where('created_at', '>=', $last14days)->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->where('created_at', '>=', $last14days)->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //last 30 days
    public function staffLast30DaysRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'last 30 days';
        ///////////////////////////////////////////////////////////////////////

        //$last7days = Carbon::now()->subDays(7);
        $last30days = Carbon::today()->subDays(30);
        //$users = User::where('created_at','>=',$date)->get();
        
        $expenses = Expense::where('staff_id', $authUser->id)->where('created_at', '>=', $last30days)->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->where('created_at', $last30days)->count();
        $newOrders = $authUser->assignedOrders()->where('status', 'new')->where('created_at', '>=', $last30days)->count();
        $pendingOrders = $authUser->assignedOrders()->where('status', 'pending')->where('created_at', '>=', $last30days)->count();
        $remittedOrders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->where('created_at', '>=', $last30days)->count();
        $notRemittedOrders = $authUser->assignedOrders()->where('status', 'delivered_not_remitted')->where('created_at', '>=', $last30days)->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->where('created_at', '>=', $last30days)->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //this week
    public function staffWeeklyRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'this week';
        ///////////////////////////////////////////////////////////////////////

        //$last7days = Carbon::now()->subDays(7);
        $last30days = Carbon::today()->subDays(30);
        //$users = User::where('created_at','>=',$date)->get();

        $dt = Carbon::now();
        
        $expenses = Expense::where('staff_id', $authUser->id)->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->count();
        $newOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->count();
        $pendingOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->count();
        $remittedOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->count();
        $notRemittedOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->whereBetween('created_at', [$dt->copy()->startOfWeek(), $dt->copy()->endOfWeek()])->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //last week
    public function staffLastWeekRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'last week';
        ///////////////////////////////////////////////////////////////////////

        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);
        
        $expenses = Expense::where('staff_id', $authUser->id)->whereBetween('created_at', [$start_week, $end_week])->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->whereBetween('created_at', [$start_week, $end_week])->count();
        $newOrders = $authUser->assignedOrders()->whereBetween('created_at', [$start_week, $end_week])->count();
        $pendingOrders = $authUser->assignedOrders()->whereBetween('created_at', [$start_week, $end_week])->count();
        $remittedOrders = $authUser->assignedOrders()->whereBetween('created_at', [$start_week, $end_week])->count();
        $notRemittedOrders = $authUser->assignedOrders()->whereBetween('created_at', [$start_week, $end_week])->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->whereBetween('created_at', [$start_week, $end_week])->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //this month
    public function staffMonthlyRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'this month';
        ///////////////////////////////////////////////////////////////////////

        $dt = Carbon::now();
        
        $expenses = Expense::where('staff_id', $authUser->id)->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->count();
        $newOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->count();
        $pendingOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->count();
        $remittedOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->count();
        $notRemittedOrders = $authUser->assignedOrders()->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->whereBetween('created_at', [$dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()])->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    //last month
    public function staffLastMonthRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'this month';
        ///////////////////////////////////////////////////////////////////////

        $dt = Carbon::now();
        
        $expenses = Expense::where('staff_id', $authUser->id)->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->sum('amount');
        $expenses = $this->shorten($expenses);
        
        $allOrders = $authUser->assignedOrders()->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $newOrders = $authUser->assignedOrders()->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $pendingOrders = $authUser->assignedOrders()->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $remittedOrders = $authUser->assignedOrders()->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $notRemittedOrders = $authUser->assignedOrders()->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $deliveredOrders = $remittedOrders + $notRemittedOrders;
        $cancelledOrders = $authUser->assignedOrders()->where('status', 'cancelled')->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        
        //orders whose dates are greater-than today
        $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
        
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->gt($today);
            if ($result) {
                $totalFollowUpOrders1[] = $order;
            }
        } 
        $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
        
        //today only
        $todayFollowUpOrders1 = [];
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date);
            $result = $expected_date->isToday();
            if ($result) {
                $todayFollowUpOrders1[] = $order;
            }
        }
        $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
        
        //tomorrow only
        $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
        foreach ($authUser->assignedOrders as $order) {
            $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
            if ($expected_date == $tomorrow) {
                $tomorrowFollowUpOrders1[] = $order;
            }
        }
        $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

        $otherOrders = $authUser->assignedOrders()->where(['customer_id'=>null])->count();

        //////////////////////////////chart/////////////////////////////////////////////////////
        
        $sales_paid = 0;
        $delivered_and_remitted_orders = $authUser->assignedOrders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

        $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
        $sales_paid = $this->shorten($sales_paid);

        // yearly report
        for ( $i = 1; $i <= 12; $i++ ) {
            $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
                ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
                ->sum('amount_accrued');
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    public function shorten($num, $digits = 1) {
        $num = preg_replace('/[^0-9]/','',$num);
        if ($num >= 1000000000) {
            $num = number_format(abs($num / 1000000000), $digits, '.', '') + 0;
            $num = $num . "b";
        }
        if ($num >= 1000000) {
            $num = number_format(abs($num / 1000000), $digits, '.', '') + 0;
            $num = $num . 'm';
        }
        if ($num >= 1000) {
            $num = number_format(abs( (int) $num / 1000), $digits, '.', '') + 0;
            $num = $num . 'k';
        }
        return $num;
    }

}
