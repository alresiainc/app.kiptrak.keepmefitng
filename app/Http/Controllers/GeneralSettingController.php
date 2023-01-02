<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\GeneralSetting;
use App\Models\Country;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generalSetting()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $generalSetting = '';
        $row = GeneralSetting::where('id', '>', 0);
        if ($row->exists()) {
            $generalSetting = $row->first();
        }

        $countries = Country::all();

        return view('pages.settings.generalSetting.generalSetting', compact('authUser', 'user_role', 'generalSetting', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generalSettingPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        if (empty($data['generalSetting'])) {
            $generalSetting = new GeneralSetting();
            $generalSetting->site_title = $data['site_title'];
            $generalSetting->site_description = $data['site_description'];
            $generalSetting->currency = $data['currency'];
            $generalSetting->developed_by = 'Ugo Sunday Raphael';
            $generalSetting->official_notification_email = $data['official_notification_email'];
            $generalSetting->created_by = 1;
            $generalSetting->status = 'true';
            
            if ($request->site_logo) {
                $imageName = time().'.'.$request->site_logo->extension();
                //store products in folder
                $request->site_logo->storeAs('generalSetting', $imageName, 'public');
                $generalSetting->site_logo = $imageName;
            }

            $generalSetting->save();

            return back()->with('success', 'Settings Saved Successfully');
        }

        $generalSetting = GeneralSetting::where('id',$data['generalSetting'])->first();
        $generalSetting->site_title = $data['site_title'];
        $generalSetting->site_description = $data['site_description'];
        $generalSetting->currency = $data['currency'];
        $generalSetting->developed_by = 'Ugo Sunday Raphael';
        $generalSetting->official_notification_email = $data['official_notification_email'];
        $generalSetting->attendance_time = $data['attendance_time'];
        $generalSetting->created_by = 1;
        $generalSetting->status = 'true';
        
        //image
        if ($request->site_logo) {
            
            $oldImage = $generalSetting->site_logo; //1.jpg
            if(Storage::disk('public')->exists('generalSetting/'.$oldImage)){
                Storage::disk('public')->delete('generalSetting/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->site_logo->extension();
            //store products in folder
            $request->site_logo->storeAs('generalSetting', $imageName, 'public');
            $generalSetting->site_logo = $imageName;
        }

        $generalSetting->save();

        return back()->with('success', 'Settings Updated Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        //
    }
}
