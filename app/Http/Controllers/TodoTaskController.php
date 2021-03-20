<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\TodoTaskStoreRequest;
use App\Http\Requests\Todo\TodoTaskUpdateRequest;
use App\Http\Resources\TodoTaskResource;
use App\Models\Todo;
use App\Models\TodoTask;
use Illuminate\Http\Request;

class TodoTaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Todo $todo, TodoTaskStoreRequest $request)
    {
        $this->authorize('view', $todo);
        $input = $request->validated();
        $todoTask = $todo->tasks()->create($input);

        return new TodoTaskResource($todoTask);
    }

    public function update(TodoTask $todoTask, TodoTaskUpdateRequest $request)
    {
        $this->authorize('update', $todoTask);
        $input = $request->validated();
        $todoTask->update($input);

        return new TodoTaskResource($todoTask);
    }

    public function destroy(TodoTask $todoTask)
    {
        $this->authorize('update', $todoTask);
        $todoTask->delete();

        return response()->noContent();
    }
}
