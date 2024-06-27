<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\GetCategoryListRequest;
use App\Http\Requests\TaskTitle\UpdateCategoryListRequest;
use App\Services\CategoryService;
use App\Traits\ApiResponder;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use ApiResponder;

    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    public function create(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        $response = $this->categoryService->create($data);
        if ($response['status'] != Response::HTTP_CREATED) {
            return $this->failed($response['message']);
        }

        return $this->success(message: $response['message'], statusCode: $response['status']);
    }

    public function getList(GetCategoryListRequest $request)
    {
        $data = $request->validated();
        $response = $this->categoryService->getList($data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message'], $response['status']);
        }
        return $this->success($response['data']);
    }

    public function get(string $categoryHashId)
    {
        $response = $this->categoryService->get($categoryHashId);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }
        return $this->success($response['data']);
    }

    public function update(string $categoryHashId, UpdateCategoryListRequest $request)
    {
        $data = $request->validated();
        $response = $this->categoryService->update($categoryHashId, $data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }

        return $this->success(message: $response['message'], statusCode: $response['status']);
    }

    public function delete(string $categoryHashId)
    {
        $response = $this->categoryService->delete($categoryHashId);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['message']);
        }
        return $this->success(message: $response['message'], statusCode: $response['status']);
    }
}
