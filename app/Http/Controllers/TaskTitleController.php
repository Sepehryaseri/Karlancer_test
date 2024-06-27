<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskTitle\CreateTaskTitleRequest;
use App\Http\Requests\TaskTitle\GetTaskTitleListRequest;
use App\Http\Requests\TaskTitle\UpdateTaskTitleListRequest;
use App\Services\TaskTitleService;
use App\Traits\ApiResponder;
use Symfony\Component\HttpFoundation\Response;

class TaskTitleController extends Controller
{
    use ApiResponder;

    public function __construct(private readonly TaskTitleService $taskTitleService)
    {
    }

    public function create(CreateTaskTitleRequest $request)
    {
        $data = $request->validated();
        $response = $this->taskTitleService->create($data);
        if ($response['status'] != Response::HTTP_CREATED) {
            return $this->failed($response['message']);
        }

        return $this->success(message: $response['message'], statusCode: $response['status']);
    }

    public function getList(GetTaskTitleListRequest $request)
    {
        $data = $request->validated();
        $response = $this->taskTitleService->getList($data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message'], $response['status']);
        }
        return $this->success($response['data']);
    }

    public function get(string $taskTitleId)
    {
        $response = $this->taskTitleService->get($taskTitleId);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }
        return $this->success($response['data']);
    }

    public function update(string $taskTitleId, UpdateTaskTitleListRequest $request)
    {
        $data = $request->validated();
        $response = $this->taskTitleService->update($taskTitleId, $data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }

        return $this->success(message: $response['message'], statusCode: $response['status']);
    }

    public function delete(string $taskTitleId)
    {
        $response = $this->taskTitleService->delete($taskTitleId);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }
        return $this->success(message: $response['message'], statusCode: $response['status']);
    }
}
