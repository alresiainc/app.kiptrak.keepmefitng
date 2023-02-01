<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectController extends Controller
{
    //
    public function overview()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $backlog_tasks = Task::where('status', 'backlog')->get();
        $pending_tasks = Task::where('status', 'pending')->get();
        $in_progress_tasks = Task::where('status', 'in_progress')->get();
        $done_tasks = Task::where('status', 'done')->get();

        return view('pages.taskManager.projects.overview', compact('authUser', 'user_role', 'backlog_tasks', 'pending_tasks', 'in_progress_tasks', 'done_tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addProject()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        $employees = User::where('type', 'staff')->get();

        return view('pages.taskManager.projects.addProject', compact('authUser', 'user_role', 'employees'));
    }

    public function addProjectPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'name' => 'required|string|unique:projects',
            'description' => 'nullable|string',
            'end_date' => 'required|date',
            'start_date' => 'required|date|before:end_date',
            'assigned_staff' => 'required|string',
            'logo' => 'nullable|image|max:2048|mimes:jpg, jpeg, png, gif, svg, webp',
        ]);

        
        $data = $request->all();

        $project = new Project();
        $project->name = $data['name'];
        $project->description = $data['description'] ?? null;
        $project->start_date = $data['start_date'];
        $project->end_date = $data['end_date'];
        $project->priority = $data['priority'];
        $project->assigned_to = $data['assigned_staff'];
        $project->created_by = $authUser->id;
        $project->status = 'pending';  
          
        if ($request->logo) {
            $imageName = time().'.'.$request->logo->extension();
            //store projects in folder
            $request->logo->storeAs('projects', $imageName, 'public');
            $project->logo = $imageName;
        }

        $project->save();

        return back()->with('success', 'Project Saved Successfully');
        

    }

    public function allProject()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $projects = Project::all();
        $employees = User::where('type', 'staff')->get();

        return view('pages.taskManager.projects.allProject', compact('authUser', 'user_role', 'projects', 'employees'));
    }

    public function singleProject($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $project = Project::where(['unique_key'=>$unique_key]);
        if(!$project->exists()){
            abort(404);
        }

        $project = $project->first();

        $backlog_tasks = $project->tasks()->where('status', 'backlog')->get();
        $pending_tasks = $project->tasks()->where('status', 'pending')->get();
        $in_progress_tasks = $project->tasks()->where('status', 'in_progress')->get();
        $done_tasks = $project->tasks()->where('status', 'done')->get();
        
        return view('pages.taskManager.projects.singleProject', compact('authUser', 'user_role', 'project', 'backlog_tasks', 'pending_tasks', 'in_progress_tasks', 'done_tasks'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProject($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $project = Project::where(['unique_key'=>$unique_key]);
        if(!$project->exists()){
            abort(404);
        }

        $project = $project->first();
        $employees = User::where('type', 'staff')->get();

        return view('pages.taskManager.projects.editProject', compact('authUser', 'user_role', 'project', 'employees'));
    }

    public function editProjectPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $project = Project::where(['unique_key'=>$unique_key]);
        if(!$project->exists()){
            abort(404);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'end_date' => 'required|date',
            'start_date' => 'required|date|before:end_date',
            'assigned_staff' => 'required|string',
            'logo' => 'nullable|image|max:2048|mimes:jpg, jpeg, png, gif, svg, webp',
        ]);

        
        $data = $request->all();

        $project = $project->first();
        $project->name = $data['name'];
        $project->description = $data['description'] ?? null;
        $project->start_date = $data['start_date'];
        $project->end_date = $data['end_date'];
        $project->priority = $data['priority'];
        $project->assigned_to = $data['assigned_staff'];
        $project->created_by = $authUser->id;
        $project->status = $data['status']; 
          
        //logo
        if ($request->logo) {
            $oldImage = $project->logo; //1.jpg
            if(Storage::disk('public')->exists('projects/'.$oldImage)){
                Storage::disk('public')->delete('projects/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->logo->extension();
            //store projects in folder
            $request->logo->storeAs('projects', $imageName, 'public');
            $project->logo = $imageName;
        }

        $project->save();

        return back()->with('success', 'Project Updated Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProject($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $project = Project::where(['unique_key'=>$unique_key]);
        if(!$project->exists()){
            abort(404);
        }

        $project = $project->first();
        $oldImage = $project->logo;
        if(Storage::disk('public')->exists('projects/'.$oldImage)){
            Storage::disk('public')->delete('projects/'.$oldImage);
        }

        if (count($project->tasks) > 0) {
            foreach ($project->tasks as $key => $task) {
                $oldImage = $task->logo;
                if(Storage::disk('public')->exists('tasks/'.$oldImage)){
                    Storage::disk('public')->delete('tasks/'.$oldImage);
                }
                $task->delete();
            }
        }
        //$project->tasks->delete();
        $project->delete();

        return back()->with('success', 'Project Removed Successfully');
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
