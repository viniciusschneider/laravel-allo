<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
	protected $entity;

    public function __construct(Task $task)
    {
        $this->entity = $task;
    }

    public function create($data)
    {
        return $this->entity->create($data);
    }

    public function update($taskId, $data)
    {
        return $this->entity->where('id', $taskId)->update($data);
    }

    public function delete($taskId)
    {
        return $this->entity->where('id', $taskId)->delete();
    }

    public function list($userId)
    {
        return $this->entity->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public function details($taskId)
    {
        return $this->entity->firstWhere('id', $taskId);
    }

    public function userHasTask($userId, $taskId)
    {
        return $this->entity->firstWhere([
            'user_id' => $userId,
            'id' => $taskId
        ]);
    }
}
