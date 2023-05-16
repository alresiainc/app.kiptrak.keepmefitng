<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

use App\Models\Permission;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        //----Task Manager-22----------------------
        $permission = new Permission();
        $permission->name = 'Task Manager Menu';
        $permission->slug = Str::slug('Task Manager Menu');
        $permission->created_by = 1;
        $permission->save();

        //----------Task Manager---Id-22--------------------
        $permission = new Permission();
        $permission->name = 'View Project List';
        $permission->slug = Str::slug('View Project List');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Project';
        $permission->slug = Str::slug('Create Project');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Project';
        $permission->slug = Str::slug('Edit Project');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Delete Project';
        $permission->slug = Str::slug('Delete Project');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Task List';
        $permission->slug = Str::slug('View Task List');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Task';
        $permission->slug = Str::slug('Create Task');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Task';
        $permission->slug = Str::slug('Edit Task');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Delete Task';
        $permission->slug = Str::slug('Delete Task');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCkeditor()
    {
        return view('ckeditor');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCkeditorPost(Request $request): JsonResponse
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('media'), $fileName);
            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
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
