<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payroll;
use App\Models\User;
use App\Models\GeneralSetting;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allPayroll()
    {
        $payrolls = Payroll::all();
        return view('pages.hrm.payroll.allPayroll', compact('payrolls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPayroll()
    {
        $staffs = User::where('type', 'staff')->get();
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();

        return view('pages.hrm.payroll.addPayroll', compact('staffs', 'generalSetting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPayrollPost(Request $request)
    {
        $request->validate([
            'employee' => 'required|string',
            'amount' => 'required|numeric',
            'paying_method' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $data = $request->all();

        $payroll = new Payroll();
        $payroll->employee_id = $data['employee'];
        $payroll->amount = $data['amount'];
        $payroll->paying_method = $data['paying_method'];
        $payroll->note = !empty($data['note']) ? $data['note'] : null;
        $payroll->save();

        return back()->with('success', 'Payroll Saved Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPayroll($unique_key)
    {
        $payroll = Payroll::where('unique_key', $unique_key);
        if(!$payroll->exists()){
            abort(404);
        }
        $payroll = $payroll->first();

        $staffs = User::where('type', 'staff')->get();
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();

        return view('pages.hrm.payroll.editPayroll', compact('staffs', 'generalSetting', 'payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPayrollPost(Request $request, $unique_key)
    {
        $request->validate([
            'employee' => 'required|string',
            'amount' => 'required|numeric',
            'paying_method' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $data = $request->all();

        $payroll = Payroll::where('unique_key', $unique_key);
        if(!$payroll->exists()){
            abort(404);
        }
        $payroll = $payroll->first();

        $payroll->employee_id = $data['employee'];
        $payroll->amount = $data['amount'];
        $payroll->paying_method = $data['paying_method'];
        $payroll->note = !empty($data['note']) ? $data['note'] : null;
        $payroll->save();

        return back()->with('success', 'Payroll Updated Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePayroll($unique_key)
    {
        $payroll = Payroll::where('unique_key', $unique_key);
        if(!$payroll->exists()){
            abort(404);
        }
        $payroll = $payroll->first();
        $payroll->delete();

        return back()->with('success', 'Payroll Removed Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
