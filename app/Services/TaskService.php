<?php

namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Repositories\TaskRepository;
use App\Traits\ErrorsExceptionsTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskService
{
    use ErrorsExceptionsTrait;

    protected $taskRepository;

    public function __construct(
        TaskRepository $taskRepository
    )
    {
        $this->taskRepository = $taskRepository;
    }

    public function create(TaskRequest $request)
    {
        $userId = JWTAuth::user()->id;
        return $this->taskRepository->create(
            [
                'description' => $request->description,
                'title' => $request->title,
                'user_id' => $userId,
            ]
        );
    }

    public function update(TaskRequest $request)
    {
        return $this->taskRepository->update($request->id, [
            'description' => $request->description,
            'title' => $request->title,
        ]);
    }

    public function delete($taskId)
    {
        return $this->taskRepository->delete($taskId);
    }

    public function list()
    {
        $userId = JWTAuth::user()->id;
        return $this->taskRepository->list($userId);
    }

    public function details($taskId)
    {
        return $this->taskRepository->details($taskId);
    }

    public function userHasTask($userId, $taskId)
    {
        return $this->taskRepository->userHasTask($userId, $taskId);
    }
}
