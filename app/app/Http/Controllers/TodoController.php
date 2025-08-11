<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        return response()->json([
            'data'    => $tasks,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'required|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|boolean',
        ]);

        $task = Task::create($validated);

        return response()->json([
            'data' => $task,
        ], 201);
    }

    public function show(int|string $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        return response()->json([
            'data' => $task,
        ], 200);
    }

    public function update(Request $request, int|string $id): JsonResponse
    {
        $validated = $request->validate([
            'title'        => 'required|max:255',
            'description'  => 'nullable|string',
            'status'       => 'boolean',
        ]);

        $task = Task::findOrFail($id);

        $task->update($validated);

        return response()->json([
            'data' => $task,
        ], 200);
    }

    public function destroy(int|string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([], 200);
    }
}
