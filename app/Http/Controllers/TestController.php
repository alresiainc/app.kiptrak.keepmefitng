<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

use App\Models\Permission;

class TestController extends Controller
{
    public function staffLast7DaysRecord()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency = $generalSetting->country->symbol;
        $record = 'last 7 days';
        ///////////////////////////////////////////////////////////////////////

        if ($authUser->isSuperAdmin || $user_role->permissions->contains('slug', 'view-staff-dashboard')) {

            //$last7days = Carbon::now()->subDays(7);
            $last7days = Carbon::today()->subDays(7);
            //$users = User::where('created_at','>=',$date)->get();
            
            $expenses = Expense::where('staff_id', $authUser->id)->where('created_at', '>=', $last7days)->sum('amount');
            $expenses = $this->shorten($expenses);
            
            $allOrders = Order::where('created_at', $last7days)->count();
            $newOrders = Order::where('status', 'new')->where('created_at', '>=', $last7days)->count();
            $pendingOrders = Order::where('status', 'pending')->where('created_at', '>=', $last7days)->count();
            $remittedOrders = Order::where('status', 'delivered_and_remitted')->where('created_at', '>=', $last7days)->count();
            $notRemittedOrders = Order::where('status', 'delivered_not_remitted')->where('created_at', '>=', $last7days)->count();
            $deliveredOrders = $remittedOrders + $notRemittedOrders;
            $cancelledOrders = Order::where('status', 'cancelled')->where('created_at', '>=', $last7days)->count();

            $theOrders = Order::where('status', 'new')->orWhere('status', 'pending')->orWhere('status', 'delivered_not_remitted')->get();
            
            //orders whose dates are greater-than today
            $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
            
            foreach ($theOrders as $order) {
                $expected_date = Carbon::parse($order->expected_delivery_date);
                $result = $expected_date->gt($today);
                if ($result) {
                    $totalFollowUpOrders1[] = $order;
                }
            } 
            $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
            
            //today only
            $todayFollowUpOrders1 = [];
            foreach ($theOrders as $order) {
                $expected_date = Carbon::parse($order->expected_delivery_date);
                $result = $expected_date->isToday();
                if ($result) {
                    $todayFollowUpOrders1[] = $order;
                }
            }
            $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
            
            //tomorrow only
            $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
            foreach ($theOrders as $order) {
                $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
                if ($expected_date == $tomorrow) {
                    $tomorrowFollowUpOrders1[] = $order;
                }
            }
            $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

            $otherOrders = Order::where(['customer_id'=>null])->count();

            //////////////////////////////chart/////////////////////////////////////////////////////
            
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            $sales_paid = $this->shorten($sales_paid);

            // yearly report
            // for ( $i = 1; $i <= 12; $i++ ) {
            //     $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
            //         ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            //         ->sum('amount_accrued');
            //     $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
            // }
            $yearly_sale_amount = $this->helper->yearlySalesReportChart($delivered_and_remitted_orders);

        } else {

            //$last7days = Carbon::now()->subDays(7);
            $last7days = Carbon::today()->subDays(7);
            //$users = User::where('created_at','>=',$date)->get();
            
            $expenses = Expense::where('staff_id', $authUser->id)->where('created_at', '>=', $last7days)->sum('amount');
            $expenses = $this->shorten($expenses);
            
            $allOrders = Order::where('created_at', $last7days)->count();
            $newOrders = Order::where('status', 'new')->where('created_at', '>=', $last7days)->count();
            $pendingOrders = Order::where('status', 'pending')->where('created_at', '>=', $last7days)->count();
            $remittedOrders = Order::where('status', 'delivered_and_remitted')->where('created_at', '>=', $last7days)->count();
            $notRemittedOrders = Order::where('status', 'delivered_not_remitted')->where('created_at', '>=', $last7days)->count();
            $deliveredOrders = $remittedOrders + $notRemittedOrders;
            $cancelledOrders = Order::where('status', 'cancelled')->where('created_at', '>=', $last7days)->count();
            
            //orders whose dates are greater-than today
            $totalFollowUpOrders1 = []; $today = Carbon::now(); $expected_date;
            
            foreach ($theOrders as $order) {
                $expected_date = Carbon::parse($order->expected_delivery_date);
                $result = $expected_date->gt($today);
                if ($result) {
                    $totalFollowUpOrders1[] = $order;
                }
            } 
            $totalFollowUpOrders = collect($totalFollowUpOrders1)->count();
            
            //today only
            $todayFollowUpOrders1 = [];
            foreach ($theOrders as $order) {
                $expected_date = Carbon::parse($order->expected_delivery_date);
                $result = $expected_date->isToday();
                if ($result) {
                    $todayFollowUpOrders1[] = $order;
                }
            }
            $todayFollowUpOrders = collect($todayFollowUpOrders1)->count();
            
            //tomorrow only
            $tomorrowFollowUpOrders1 = []; $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
            foreach ($theOrders as $order) {
                $expected_date = Carbon::parse($order->expected_delivery_date)->format('Y-m-d');
                if ($expected_date == $tomorrow) {
                    $tomorrowFollowUpOrders1[] = $order;
                }
            }
            $tomorrowFollowUpOrders = collect($tomorrowFollowUpOrders1)->count();

            $otherOrders = Order::where(['customer_id'=>null])->count();

            //////////////////////////////chart/////////////////////////////////////////////////////
            
            //$sales_paid = 0;
            $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
            // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');

            // $sales_paid += $accepted_outgoing_stock->sum('amount_accrued');
            $sales_paid = $this->helper->orderSalesRevenue($delivered_and_remitted_orders);
            $sales_paid = $this->shorten($sales_paid);

            // yearly report
            // for ( $i = 1; $i <= 12; $i++ ) {
            //     $sale_amount = DB::table('outgoing_stocks')->whereYear('created_at', (string) Carbon::now()->year)->whereMonth('created_at',$i)
            //         ->whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted')
            //         ->sum('amount_accrued');
            //     $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
            // }
            $yearly_sale_amount = $this->helper->yearlySalesReportChart($delivered_and_remitted_orders);

        }

        return view('pages.staffDashboardDuration', compact('authUser', 'user_role', 'generalSetting', 'currency', 'record',
        'allOrders', 'newOrders', 'allOrders', 'pendingOrders', 'remittedOrders', 'notRemittedOrders', 'deliveredOrders', 'cancelledOrders', 
        'totalFollowUpOrders', 'todayFollowUpOrders', 'tomorrowFollowUpOrders', 'otherOrders', 'sales_paid', 'expenses',
        'yearly_sale_amount'));
    }

    

}
