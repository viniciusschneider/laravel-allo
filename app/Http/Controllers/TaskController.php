<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(
        TaskService $taskService
    )
    {
        $this->taskService = $taskService;
    }

    public function create(TaskRequest $request)
    {
        $this->taskService->create($request);
        return response()->json([], 201);
    }

    public function update(TaskRequest $request)
    {
        $this->taskService->update($request);
    }

    public function delete(TaskRequest $request)
    {
        $this->taskService->delete($request->id);
    }

    public function list()
    {
        return response()->json($this->taskService->list());
    }

    public function details(TaskRequest $request)
    {
        return response()->json($this->taskService->details($request->id));
    }
}
