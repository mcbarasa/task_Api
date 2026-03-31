<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'due_date', 'priority', 'status'];

    // Priority order for sorting
    public static array $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];

    // Valid status transitions
    public static array $statusFlow = [
        'pending'     => 'in_progress',
        'in_progress' => 'done',
    ];
}