<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // 1. Create Task - POST /api/tasks
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
        ]);

        // Check for duplicate title on the same due_date
        $duplicate = Task::where('title', $validated['title'])
            ->where('due_date', $validated['due_date'])
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'A task with the same title already exists for this due date.'
            ], 422);
        }

        $task = Task::create([
            'title'    => $validated['title'],
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
            'status'   => 'pending',
        ]);

        return response()->json($task, 201);
    }

    // 2. List Tasks - GET /api/tasks
    public function index(Request $request): JsonResponse
    {
        $query = Task::query();

        // Optional status filter
        if ($request->has('status')) {
            $request->validate([
                'status' => Rule::in(['pending', 'in_progress', 'done']),
            ]);
            $query->where('status', $request->status);
        }

        // Sort: priority (high→low), then due_date ascending
        $tasks = $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('due_date', 'asc')
            ->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found.',
                'data'    => []
            ], 200);
        }

        return response()->json($tasks, 200);
    }

    // 3. Update Task Status - PATCH /api/tasks/{id}/status
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $nextStatus = Task::$statusFlow[$task->status] ?? null;

        if (!$nextStatus) {
            return response()->json([
                'message' => 'Task is already marked as done. No further status updates allowed.'
            ], 422);
        }

        $task->update(['status' => $nextStatus]);

        return response()->json([
            'message' => "Status updated to '{$nextStatus}'.",
            'task'    => $task
        ], 200);
    }

    // 4. Delete Task - DELETE /api/tasks/{id}
    public function destroy(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        if ($task->status !== 'done') {
            return response()->json([
                'message' => 'Only tasks with status "done" can be deleted.'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.'
        ], 200);
    }

    // 5. Bonus: Daily Report - GET /api/tasks/report?date=YYYY-MM-DD
    public function report(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|date_format:Y-m-d',
        ]);

        $date  = $request->query('date');
        $tasks = Task::where('due_date', $date)->get();

        $priorities = ['high', 'medium', 'low'];
        $statuses   = ['pending', 'in_progress', 'done'];

        $summary = [];
        foreach ($priorities as $priority) {
            foreach ($statuses as $status) {
                $summary[$priority][$status] = $tasks
                    ->where('priority', $priority)
                    ->where('status', $status)
                    ->count();
            }
        }

        return response()->json([
            'date'    => $date,
            'summary' => $summary
        ], 200);
    }
}