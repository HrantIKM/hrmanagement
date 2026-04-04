<?php

namespace App\Models\Payslip;

use App\Models\Base\BaseModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends BaseModel
{
    /**
     * @var string[]
     */
    protected $appends = [
        'period_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'period_month',
        'period_year',
        'base_amount',
        'bonus',
        'deductions',
        'net_total',
        'pdf_path',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'base_amount' => 'decimal:2',
            'bonus' => 'decimal:2',
            'deductions' => 'decimal:2',
            'net_total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function periodDisplay(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!$this->period_month || !$this->period_year) {
                    return '';
                }

                return date('F', mktime(0, 0, 0, (int) $this->period_month, 1)) . ' ' . $this->period_year;
            }
        );
    }
}
