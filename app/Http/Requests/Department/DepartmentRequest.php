<?php

namespace App\Http\Requests\Department;

use App\Models\Department\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('parent_id') && $this->input('parent_id') === '') {
            $this->merge(['parent_id' => null]);
        }
    }

    public function rules(): array
    {
        $department = $this->route('department');
        $currentId = $department instanceof Department ? (int) $department->id : null;

        return [
            'name' => [
                'required',
                'string_with_max',
                Rule::unique(Department::getTableName(), 'name')->ignore($department),
            ],
            'description' => 'nullable|string_with_max',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists(Department::getTableName(), 'id'),
                function (string $attribute, mixed $value, \Closure $fail) use ($currentId): void {
                    if ($value === null || $value === '') {
                        return;
                    }
                    $pid = (int) $value;
                    if ($currentId !== null && $pid === $currentId) {
                        $fail(__('validation.distinct'));

                        return;
                    }
                    if ($currentId !== null && $this->isDescendantOf($pid, $currentId)) {
                        $fail(__('department.invalid_parent_hierarchy'));
                    }
                },
            ],
            'icon' => 'required|string_with_max',
        ];
    }

    private function isDescendantOf(int $ancestorId, int $possibleDescendantId): bool
    {
        $visited = [];
        $id = $ancestorId;
        while ($id !== 0) {
            if (isset($visited[$id])) {
                return false;
            }
            $visited[$id] = true;
            if ($id === $possibleDescendantId) {
                return true;
            }
            $id = (int) (Department::query()->whereKey($id)->value('parent_id') ?? 0);
        }

        return false;
    }
}
