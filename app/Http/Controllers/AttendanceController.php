<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allAttendance()
    {
        $attendances = Attendance::all();
        return view('pages.hrm.attendance.allAttendance', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addAttendance()
    {
        $staffs = User::where('type', 'staff')->get();
        return view('pages.hrm.attendance.addAttendance', compact('staffs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAttendancePost(Request $request)
    {
        $request->validate([
            'employee' => 'required|string',
            'check_in' => 'required|string',
            'check_out' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $data = $request->all();

        $attendance = new Attendance();
        $attendance->employee_id = $data['employee'];
        $attendance->check_in = !empty($data['check_in']) ? $data['check_in'] : null;
        $attendance->check_out = !empty($data['check_out']) ? $data['check_out'] : null;
        $attendance->daily_status = 'present'; //absent, late
        $attendance->note = !empty($data['note']) ? $data['note'] : null;
        $attendance->save();

        return back()->with('success', 'Attendance Saved Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
