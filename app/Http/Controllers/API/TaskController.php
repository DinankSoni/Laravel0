<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    // GET /tasks?completed=true|false&sort=due_date&direction=asc|desc
    public function index(Request $request)
    {
        $user = $request->user(); // Get authenticated user

        // Get query parameters
        $completed = $request->query('completed', null);
        $sort = $request->query('sort', null);
        $direction = $request->query('direction', 'asc');

        // Start query builder for current user's tasks
        $query = Task::where('user_id', $user->id);

        // Apply sorting if requested
        // accept both 'due_date' and common typo 'due_dat' per spec
        if ($sort && in_array($sort, ['due_date','due_dat'])) {
            $query = $query->sortByDueDate($direction);
        }

        // Filter by completion status if provided
        $query = $query->completed($completed);

        // Execute query with pagination
        $tasks = $query->paginate(15); // or ->get()

        return response()->json($tasks);
    }

    // Create a new task
    public function store(StoreTaskRequest $request)
    {
        $user = $request->user();

        // Create task with validated data and associate with user
        $task = Task::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return response()->json($task, 201);
    }

    // Get a specific task
    public function show(Request $request, Task $task)
    {
        // Verify user owns this task
        Gate::authorize('view', $task);
        return response()->json($task);
    }

    // Update a specific task
    public function update(UpdateTaskRequest $request, Task $task)
    {
        // Verify user owns this task
        Gate::authorize('update', $task);

        // Update with validated data
        $task->update($request->validated());

        return response()->json($task);
    }

    // Delete a specific task
    public function destroy(Request $request, Task $task)
    {
        // Verify user owns this task
        Gate::authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Deleted']);
    }

    // PATCH /tasks/{id}/complete
    public function complete(Request $request, Task $task)
    {
        // Verify user owns this task
        Gate::authorize('complete', $task);

        // Mark as completed
        $task->is_completed = true;
        $task->save();

        return response()->json($task);
    }
}
