<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\TaskCategory;
use App\Models\TaskRemark;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTask() {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        if ($authUser->isSuperAdmin) {
            $tasks = Task::all();
        } else {
            //as creator, or assigned-to
            $myTasks = Task::where('created_by', $authUser->id)->orWhere('assigned_to', $authUser->id)->get();
            $tasks = $myTasks;

            //as team leader
            if (count($myTasks) == 0) {
                $project_ids = $authUser->projects->pluck('id');
                $teamLeaderTasks = Task::whereIn('project_id', $project_ids)->get();
                $tasks = $teamLeaderTasks;
            }
        }

        return view('pages.taskManager.tasks.allTask', compact('authUser', 'user_role', 'tasks'));
    }

    public function addTask() {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $projects = Project::all();
        $employees = User::where('type', 'staff')->get();
        $categories = TaskCategory::all();

        return view('pages.taskManager.tasks.addTask', compact('authUser', 'user_role', 'projects', 'employees', 'categories'));
    }

    public function addTaskPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'name' => 'required|string',
            'project' => 'required',
            'description' => 'nullable|string',
            'end_date' => 'required|date',
            'start_date' => 'required|date|before:end_date',
            'assigned_team_member' => 'required|string',
            'task_category' => 'nullable',
            'logo' => 'nullable|image|max:2048|mimes:jpg, jpeg, png, gif, svg, webp',
        ]);

        
        $data = $request->all();

        $task = new Task();
        $task->name = $data['name'];
        $task->project_id = $data['project'];
        $task->description = $data['description'] ?? null;
        $task->start_date = $data['start_date'];
        $task->end_date = $data['end_date'];
        $task->priority = $data['priority'];
        $task->assigned_to = $data['assigned_team_member'];
        $task->category_id = $data['task_category'] ?? null;
        $task->created_by = $authUser->id;
        $task->status = 'pending';  
          
        if ($request->logo) {
            $imageName = time().'.'.$request->logo->extension();
            //store tasks in folder
            $request->logo->storeAs('tasks', $imageName, 'public');
            $task->logo = $imageName;
        }

        $task->save();

        return back()->with('success', 'Task Saved Successfully');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function singleTask($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $task = Task::where(['unique_key'=>$unique_key]);
        if(!$task->exists()){
            abort(404);
        }
        
        $task = $task->first();
        $project = $task->project;
        $employees = User::where('type', 'staff')->get();

        $remarks = $task->remarks;

        return view('pages.taskManager.tasks.singleTask', compact('authUser', 'user_role', 'task', 'project', 'remarks', 'employees'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editTask($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $task = Task::where(['unique_key'=>$unique_key]);
        if(!$task->exists()){
            abort(404);
        }
        $task = $task->first();

        $projects = Project::all();
        $employees = User::where('type', 'staff')->get();
        $categories = TaskCategory::all();

        return view('pages.taskManager.tasks.editTask', compact('authUser', 'user_role', 'task', 'projects', 'employees', 'categories'));
    }

    public function editTaskPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $task = Task::where(['unique_key'=>$unique_key]);
        if(!$task->exists()){
            abort(404);
        }
        
        $request->validate([
            'name' => 'required|string',
            'project' => 'required',
            'description' => 'nullable|string',
            'end_date' => 'required|date',
            'start_date' => 'required|date|before:end_date',
            'assigned_team_member' => 'required|string',
            'task_category' => 'nullable',
            'logo' => 'nullable|image|max:2048|mimes:jpg, jpeg, png, gif, svg, webp',
        ]);

        
        $data = $request->all();

        $task = $task->first();
        $task->name = $data['name'];
        $task->project_id = $data['project'];
        $task->description = $data['description'] ?? null;
        $task->start_date = $data['start_date'];
        $task->end_date = $data['end_date'];
        $task->priority = $data['priority'];
        $task->assigned_to = $data['assigned_team_member'];
        $task->category_id = $data['task_category'] ?? null;
        $task->created_by = $authUser->id;
        $task->status = $data['status']; 
          
        //logo
        if ($request->logo) {
            $oldImage = $task->logo; //1.jpg
            if(Storage::disk('public')->exists('tasks/'.$oldImage)){
                Storage::disk('public')->delete('tasks/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->logo->extension();
            //store tasks in folder
            $request->logo->storeAs('tasks', $imageName, 'public');
            $task->logo = $imageName;
        }

        $task->save();

        return back()->with('success', 'Task Updated Successfully');
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTask($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $task = Task::where(['unique_key'=>$unique_key]);
        if(!$task->exists()){
            abort(404);
        }
        $task = $task->first();

        $oldImage = $task->logo;
        if(Storage::disk('public')->exists('tasks/'.$oldImage)){
            Storage::disk('public')->delete('tasks/'.$oldImage);
        }

        $task->delete();
        return back()->with('success', 'Task Removed Successfully');
    }
    
    //task remark, commenting
    public function taskRemarkPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'remark' => 'required|string|max:100',
        ]);

        $task = Task::where(['unique_key'=>$unique_key]);
        if(!$task->exists()){
            abort(404);
        }
        $task = $task->first();
        $data = $request->all();

        $taskRemark = new TaskRemark();
        $taskRemark->remark = $data['remark'];
        $taskRemark->task_id = $task->id;
        $taskRemark->created_by = $authUser->id;
        $taskRemark->save();

        return back()->with('success', 'Comment Saved Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTaskStatus($unique_key, $status)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $task = Task::where(['unique_key'=>$unique_key]);
        if(!$task->exists()){
            abort(404);
        }

        $task = $task->first();
        $task->status = $status;
        $task->save();

        return back()->with('success', 'Task Status Updated Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTaskPriority($unique_key, $priority)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $task = Task::where(['unique_key'=>$unique_key]);
        if(!$task->exists()){
            abort(404);
        }

        $task = $task->first();
        $task->priority = $priority;
        $task->save();

        return back()->with('success', 'Task Priority Updated Successfully');
    }

    //allTaskCategory
    public function allTaskCategory()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $categories = TaskCategory::all();

        return view('pages.taskManager.taskCategory.allTaskCategory', compact('authUser', 'user_role', 'categories'));
    }

    //addTaskCategoryPost
    public function addTaskCategoryPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'category_name' => 'required|string',
        ]);

        $data = $request->all();
        
        $category = new TaskCategory();
        $category->name = $data['category_name'];
        $category->created_by = $authUser->id;
        $category->status = 'true';
        $category->save();

        return back()->with('success', 'Task Added Successfully');
    }

    //editTaskCategoryPost
    public function editTaskCategoryPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'category_name' => 'required|string',
        ]);

        $data = $request->all();
        
        $category = TaskCategory::where('id', $data['category_id'])->first();
        $category->name = $data['category_name'];
        $category->created_by = $authUser->id;
        $category->status = 'true';
        $category->save();

        return back()->with('success', 'Category Updated Successfully');
    }

    //ajax
    public function ajaxCreateTaskCategory(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        
        $category = new TaskCategory();
        $category->name = $data['category_name'];
       
        $category->created_by = $authUser->id;
        $category->status = 'true';
        $category->save();
        
        //store in array
        $data['category'] = $category;

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTaskCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $category = TaskCategory::where(['unique_key'=>$unique_key]);
        if(!$category->exists()){
            abort(404);
        }
        $category = $category->first();
        $category->delete();
        return back()->with('success', 'Category Removed Successfully');
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

    // public function nLargest()
    // {
    //     // array
    //     $arr = array(2, 10, 23, 7, 47, 3, 20);

    //     $n = sizeof($arr);

    //     //can be any value, for testing sake
    //     $count_limit = 3;

    //     // Sort & reverse arr
    //     rsort($arr); 

    //     //return $arr; //[47,23,20,10,7,3,2]

    //     // largest elements, store in empty array
    //     $y = [];
    //     for ($i = 0; $i < $count_limit; $i++) {
    //         $y[] = $arr[$i] . "";
    //     }
        
    //     return $y;


    // }

    public function getN($arr, $n, $end) {

        //array count
        $count = count($arr); //7
        
        //get actual key positioning
        if($end){
            $n = $count - $n; //7-3=4 
        } else {
            $n--;
        }
        // return $n;

        //sort & reverse
        sort($arr);

        //return $arr; //[2,3,7,10,20,23,47]
        
        //return actual array item
        return $arr[$n]; //20
    }
}
