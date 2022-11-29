<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Account;
use App\Models\MoneyTransfer;
use App\Models\Payment;

class AccountController extends Controller
{
    public function allAccount()
    {
        $accounts = Account::all();
        return view('pages.accounts.allAccount', compact('accounts'));
    }

    public function addAccount()
    {
        $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
        return view('pages.accounts.addAccount', compact('account_no'));
    }

    public function addAccountPost(Request $request)
    {
        $request->validate([
            'account_no' => 'required|string|unique:accounts',
            'account_name' => 'required|string',
            'initial_balance' => 'nullable',
            'amount' => 'required|numeric',
            'note' => 'required|string',
        ]);

        $data = $request->all();
        $account = new Account();
        $account->account_no = $data['account_no'];
        $account->name = $data['account_name'];
        $account->initial_balance = !empty($data['initial_balance']) ? $data['initial_balance'] : 0;
        $account->amount_added = $data['amount_added'];
        $account->total_balance = 0;
        $account->note = !empty($data['note']) ? $data['note'] : null;
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();

        return back()->with('success', 'Account Added Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAccountAjaxPost(Request $request)
    {
        $data = $request->all();
        $account = new Account();
        $account->account_no = $data['account_no'];
        $account->name = $data['name'];
        
        if ($data['initial_balance'] == '' || $data['initial_balance'] == null) {
            $account->initial_balance = null;
        } else {
            $account->initial_balance = $data['initial_balance'];
        }

        $account->total_balance = 0;

        if ($data['note'] == '' || $data['note'] == null) {
            $account->note = null;
        } else {
            $account->note = $data['note'];
        }
         
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();

        $data['account'] = $account;

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAccount($unique_key)
    {
        $account = Account::where('unique_key', $unique_key)->first();
        if (!isset($account)) {
            abort(404);
        }

        return view('pages.accounts.editAccount', compact('account'));
    }

    public function editAccountPost(Request $request, $unique_key)
    {
        $account = Account::where('unique_key', $unique_key)->first();
        if (!isset($account)) {
            abort(404);
        }

        $request->validate([
            'account_no' => 'required|string',
            'account_name' => 'required|string',
            'initial_balance' => 'nullable',
            'amount' => 'required|numeric',
            'note' => 'required|string',
        ]);

        $data = $request->all();
        
        $account->account_no = $data['account_no'];
        $account->name = $data['account_name'];
        $account->initial_balance = !empty($data['initial_balance']) ? $data['initial_balance'] : 0;
        $account->amount_added = $data['amount'];
        $account->total_balance = 0;
        $account->note = !empty($data['note']) ? $data['note'] : null;
        $account->created_by = 1;
        $account->status = 'true';
        $account->save();

        return back()->with('success', 'Account Updated Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allMoneyTransfer()
    {
        $transfers = MoneyTransfer::all();
        //code
        $string = 'kpm-' . date("Ymd") . '-'. date("his");
        $randomStrings = MoneyTransfer::where('code', 'like', $string.'%')->pluck('code');
        do {
            $randomString = $string;
        } while ($randomStrings->contains($randomString));
    
        $code = $randomString;
        //code

        $accounts = Account::all();
        return view('pages.accounts.allMoneyTransfer', compact('transfers', 'code', 'accounts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //from acct to acct
    public function addMoneyTransferPost(Request $request)
    {
        $data = $request->all();

        $fromAccount = Account::where('id', $data['from_account'])->first();
        $toAccount = Account::where('id', $data['to_account'])->first();

        if ($fromAccount->total_balance < $data['from_account']) {
            return back()->with('error', 'Insufficient Fund in \'From Account\'');
        }

        $fromAcct_remaining = $fromAccount->total_balance - $data['amount'];
        $toAcct_current_total = $toAccount->total_balance + $data['amount'];

        $data = $request->all();
        $transfer = new MoneyTransfer();
        $transfer->code = $data['code'];
        $transfer->from_account_id = $data['from_account'];
        $transfer->to_account_id = $data['to_account'];
        $transfer->amount = $data['amount'];
        $transfer->note = !empty($data['note']) ? $data['note'] : null;
        $transfer->save();

        $fromAccount->update(['total_balance' => $fromAcct_remaining]);
        $toAccount->update(['total_balance' => $toAcct_current_total]);

        return back()->with('succces', 'Money Transferred Successfully');
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //handeld by modal
    public function balanceSheet()
    {
        $accounts = Account::all();
        $debit = [];
        $credit = [];
        foreach ($accounts as $account) {
            $payment_recieved = Payment::whereNotNull('sale_id')->where('account_id', $account->id)->sum('amount');
            $payment_sent = Payment::whereNotNull('purchase_id')->where('account_id', $account->id)->sum('amount');
            $returns = DB::table('return_sales')->where('account_id', $account->id)->sum('grand_total');
            $return_purchase = DB::table('return_purchases')->where('account_id', $account->id)->sum('grand_total');
            $expenses = DB::table('expenses')->where('account_id', $account->id)->sum('amount');
            $payrolls = DB::table('payrolls')->where('account_id', $account->id)->sum('amount');
            $sent_money_via_transfer = MoneyTransfer::where('from_account_id', $account->id)->sum('amount');
            $recieved_money_via_transfer = MoneyTransfer::where('to_account_id', $account->id)->sum('amount');

            $credit[] = $payment_recieved + $return_purchase + $recieved_money_via_transfer + $account->initial_balance;
            $debit[] = $payment_sent + $returns + $expenses + $payrolls + $sent_money_via_transfer;

            /*$credit[] = $payment_recieved + $return_purchase + $account->initial_balance;
            $debit[] = $payment_sent + $returns + $expenses + $payrolls;*/
        }
        return view('pages.accounts.balanceSheet', compact('accounts', 'debit', 'credit'));
    }
}
