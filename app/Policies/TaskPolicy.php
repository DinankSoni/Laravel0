<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    // Determine whether the user can view the model.
    public function view(User $user, Task $task)
    {
        // Only allow if the task belongs to the user
        return $user->id === $task->user_id;
    }

    // Determine whether the user can update the model.
    public function update(User $user, Task $task)
    {
        // Only allow if the task belongs to the user
        return $user->id === $task->user_id;
    }

    // Determine whether the user can delete the model.
    public function delete(User $user, Task $task)
    {
        // Only allow if the task belongs to the user
        return $user->id === $task->user_id;
    }

    // Determine whether the user can complete the task.
    public function complete(User $user, Task $task)
    {
        // Only allow if the task belongs to the user
        return $user->id === $task->user_id;
    }

    // Determine whether the user can create models.
    public function create(User $user)
    {
        // Any authenticated user can create a task
        return true;
    }
}
