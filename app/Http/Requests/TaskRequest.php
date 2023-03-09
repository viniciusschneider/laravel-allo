<?php

namespace App\Http\Requests;

use App\Services\TaskService;
use App\Traits\RequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskRequest extends FormRequest
{
    use RequestValidationTrait;

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        $routeName = Route::getCurrentRoute()->getName();
        $userId = JWTAuth::user()->id;

        if (in_array($routeName, ['tasks.delete', 'tasks.update', 'tasks.task'])) {
            $task = $this->taskService->userHasTask($userId, $request->id);
            if (!$task) return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $routeName = Route::getCurrentRoute()->getName();

        return in_array($routeName, ['tasks.create', 'tasks.update'])
            ? [
                'title' => 'required|string|min:5|max:50',
                'description' => 'required|string|min:5|max:255',
            ]
            : [];
    }
}
