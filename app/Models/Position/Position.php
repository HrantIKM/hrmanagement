<?php

namespace App\Models\Position;

use App\Models\Base\BaseModel;
use App\Models\Department\Department;
use App\Models\Position\Traits\PositionRelations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends BaseModel
{
    use PositionRelations;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'min_salary',
        'max_salary',
        'grade_level',
        'department_id',
    ];

    protected function casts(): array
    {
        return [
            'min_salary' => 'decimal:2',
            'max_salary' => 'decimal:2',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
