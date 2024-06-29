<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Services\TaskService;
use App\Traits\ApiResponder;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use ApiResponder;

    public function __construct(protected TaskService $taskService)
    {
    }

    public function create(string $taskTitleHashId, CreateTaskRequest $request)
    {
        $data = $request->validated();
        $response = $this->taskService->create($taskTitleHashId, $data);
        if ($response['status'] != Response::HTTP_CREATED) {
            return $this->failed($response['message']);
        }

        return $this->success(message: $response['message'], statusCode: $response['status']);
    }

    public function getList(string $taskTitleHashId)
    {
        $response = $this->taskService->getList($taskTitleHashId);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message'], $response['status']);
        }
        return $this->success($response['data']);
    }

    public function update(string $taskHashId, UpdateTaskRequest $request)
    {
        $data = $request->validated();
        $response = $this->taskService->update($taskHashId, $data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }

        return $this->success(message: $response['message'], statusCode: $response['status']);
    }

    public function delete(string $taskHashId)
    {
        $response = $this->taskService->delete($taskHashId);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }
        return $this->success(message: $response['message'], statusCode: $response['status']);
    }
}
