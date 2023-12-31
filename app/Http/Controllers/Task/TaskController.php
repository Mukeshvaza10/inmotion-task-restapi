<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Http\Requests\Task\TaskStoreUpdateRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $task = Task::orderBy('id', 'DESC')
                ->when(isset($request->status) && (($request->status == Task::STATUS_COMPLETED || $request->status == Task::STATUS_IN_COMPLETED)), function($query) use($request){
                    $query->where('status',$request->status);
                })
                ->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Task Record Retrive Successfully.',
            'data' => $task
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreUpdateRequest $request)
    {
        $task = Task::create($request->all());
 
        if ($task) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task Added Successfully.',
                'data' => $task
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Task not added!!!'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (!$task) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Task not found!!!'
            ], 404);
        }
 
        return response()->json([
            'status' => 'success',
            'message' => 'Task Found Successfully.',
            'data' => $task
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskStoreUpdateRequest $request, Task $task)
    {
        $task_updated = $task->update($request->all());
 
        if ($task_updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task Updated Successfully.',
                'data' => $task
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Task not added!!!'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (!$task) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Task not found'
            ], 404);
        }

        if ($task->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task Deleted Successfully!!!'
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Task can not be deleted, something went wrong please try again!!!'
            ], 500);
        }
    }

    /**
     * Update the only task status property
     */
    public function changeTaskStatus(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $status_update = $task->update($request->all());

        if ($status_update) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task Status Updated Successfully.',
                'data' => $task
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Task Status not Updated, something went wrong please try again!!!'
            ], 500);
        }
    }
}
