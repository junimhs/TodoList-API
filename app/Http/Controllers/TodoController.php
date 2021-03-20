<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\TodoCreateRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        return TodoResource::collection(auth()->user()->todos()->paginate(15));
    }

    public function show(Todo $todo)
    {
        $this->authorize('view', $todo);
        $todo->load('tasks');
        return new TodoResource($todo);
    }

    public function store(TodoCreateRequest $request)
    {
        $input = $request->validated();
        $todo = auth()->user()->todos()->create($input);

        return new TodoResource($todo);
    }

    public function update(Todo $todo, TodoCreateRequest $request)
    {
        $input = $request->validated();
        $todo->fill($input);
        $todo->save();

        return new TodoResource($todo->fresh());
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->noContent();
    }
}
