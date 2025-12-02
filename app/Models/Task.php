<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'due_date',
        'user_id',
    ];

    // Attribute casting (e.g. converting 0/1 to boolean, string to date)
    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'date',
    ];

    // Relationship
    // Relationship: A task belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for filtering/sorting
    // Scope: Filter tasks by completion status
    public function scopeCompleted(Builder $query, $value)
    {
        if ($value === null) return $query;
        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($bool === null) return $query;
        return $query->where('is_completed', $bool);
    }

    // Scope: Sort tasks by due date
    public function scopeSortByDueDate(Builder $query, $direction = 'asc')
    {
        $direction = in_array(strtolower($direction), ['asc','desc']) ? $direction : 'asc';
        return $query->orderBy('due_date', $direction);
    }
}
