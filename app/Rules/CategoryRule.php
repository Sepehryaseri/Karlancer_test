<?php

namespace App\Rules;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Traits\HashIdConverter;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryRule implements ValidationRule
{
    use HashIdConverter;

    public function __construct(public CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $categoryId = $this->deHash($value, 'category');
        if (!$categoryId) {
            $fail('category.wrong_id')->translate();
        }

        $category = $this->categoryRepository->findBY([
            ['id' , '=', $categoryId],
            ['user_id', '=', auth('sanctum')->id()]
        ]);
        if (!isset($category)) {
            $fail('category.not_match')->translate();
        }
    }
}
