<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    private $validationRules = [
        'title'        => 'required|max:255',
        'description'  => 'nullable|string|max:1000',
        'status'       => 'required|boolean',
    ];

    public function index()
    {
        $tasks = Task::all();

        return response()->json([
            'data'    => $tasks,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->validationRules);

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

    public function update(Request $request, int|string $id): JsonResponse|Response
    {
        $validated = $request->validate($this->validationRules);

        $task = Task::findOrFail($id);

        $task->update($validated);

        return response()->noContent();
    }

    public function destroy(int|string $id): JsonResponse|Response
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->noContent();
    }
}
