<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $attendances = Attendance::orderBy('id', 'DESC')->get();
        return view('pages.hrm.attendance.allAttendance', compact('authUser', 'user_role', 'attendances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addAttendance()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $dt = Carbon::now();
        $already_checked_in = Attendance::where('employee_id', $authUser->id)->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->exists();

        if ($already_checked_in) {
            return back()->with('info', 'Staff Already Checked-In Today');
        }

        $staffs = User::where('type', 'staff')->get();
        return view('pages.hrm.attendance.addAttendance', compact('authUser', 'user_role', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAttendancePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            // 'employee' => 'required|string',
            //'check_in' => 'required|string|regex:(^AM$|^PM$)',
            'check_in' => 'required|string|regex:/^((?!\.AM)(?!\.PM).)*$/',
            //'check_in' => 'required|string|in:PM',
            'check_out' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $data = $request->all();

        $dt = Carbon::now();
        $already_checked_in = Attendance::where('employee_id', $authUser->id)->whereBetween('created_at', [$dt->copy()->startOfDay(), $dt->copy()->endOfDay()])->exists();

        if ($already_checked_in) {
            return back()->with('info', 'Staff Already Checked-In Today');
        }
        //return explode(' ', $data['check_in']);
        $period = false;
        
        $actual_check_in = $data['check_in'];
        // $x = strtotime(Carbon::parse(str_replace('PM', '', $actual_check_in))->format('H:i'));
        // $y = strtotime(Carbon::parse('08:00'));
        
        $ampm = substr($actual_check_in, -2); //AM, PM

        if ($ampm=='AM') {
            $check_in = strtotime(str_replace('AM', '', $actual_check_in));
            $period = true;
        }
        
        if ($ampm=='PM') {
            $check_in = strtotime(str_replace('PM', '', $actual_check_in));
            $period = true;
        }
        
        if ($period==false) {
            return back()->with("info", "Check-In time must contain 'AM' OR 'PM'");
        }
        
        // return $check_in = strtotime($data['check_in']); //1672513500
        $check_in_24h_format = strtotime(date("G:i", $check_in)); //1672513500
        $expected_check_in = strtotime('08:00 AM'); //1673769600

        $daily_status = 'on_time';
        if ($check_in > $expected_check_in) {
            $daily_status = 'late';
        }
        if ($expected_check_in == $check_in) {
            $daily_status = 'on_time';
        }

        $attendance = new Attendance();
        $attendance->employee_id = $authUser->id;
        $attendance->check_in = !empty($data['check_in']) ? $data['check_in'] : null;
        $attendance->check_out = !empty($data['check_out']) ? $data['check_out'] : null;
        $attendance->daily_status = $daily_status; //on_time, late, absent 
        $attendance->check_in_note = !empty($data['note']) ? $data['note'] : null;
        $attendance->save();

        return back()->with('success', 'Attendance Saved Successfully');
    }

    //for exit
    public function editAttendance($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $attendance = Attendance::where('unique_key', $unique_key)->first();
        if (!isset($attendance)) {
            abort(404);
        }

        if (isset($attendance->check_out)) {
            return back()->with('info', 'Staff Already Checked-Out Today');
        }

        return view('pages.hrm.attendance.exitAttendance', compact('authUser', 'user_role', 'attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAttendancePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $attendance = Attendance::where('unique_key', $unique_key)->first();
        if (!isset($attendance)) {
            abort(404);
        }

        $request->validate([
            'employee' => 'required|string',
            'check_out' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $data = $request->all();

        $attendance->check_out = !empty($data['check_out']) ? $data['check_out'] : null;
        // $attendance->daily_status = 'present'; //absent, late
        $attendance->check_out_note = !empty($data['note']) ? $data['note'] : null;
        $attendance->save();

        return back()->with('success', 'Checkout Attendance Updated Successfully');
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
