<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\TaskSearchRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function index(TaskSearchRequest $request)
    {
        return TaskResource::collection(
            TaskService::search(
                $request->only(['id', 'search', 'status_ids']),
                Task::query()
            )
            ->orderBy('name')
            ->paginate($request->per_page ?? 12)
        );
    }
    
    public function store(StoreUserRequest $request)
    {
        $task = Task::create($request->only(['name']));

        return response()->json(new UserResource($task), 201);   
    }

    public function show(Task $task)
    {
        return response()->json(new UserResource($task));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->only(['name', 'status_id']));

        return response()->json(new TaskResource($task), 200);
    }

    public function destroy(Task $task)
    {        
        $task->delete();

        return response()->json(null, 204);   
    }
}
