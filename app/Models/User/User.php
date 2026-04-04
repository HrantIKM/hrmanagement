<?php

namespace App\Models\User;

use App\Casts\DateCast;
use App\Models\Base\Traits\HasFileData;
use App\Models\User\Enums\EmploymentStatus;
use App\Models\Base\Traits\ModelHelperFunctions;
use App\Models\File\File;
use App\Models\User\Traits\UserAccessors;
use App\Models\User\Traits\UserHelperMethods;
use App\Models\User\Traits\UserRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /**
     * @var string[]
     */
    protected $appends = [
        'employment_status_display',
    ];

    use HasApiTokens;
    use HasFileData;
    use HasRoles;
    use ModelHelperFunctions;
    use Notifiable;
    use UserAccessors;
    use UserHelperMethods;
    use UserRelations;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'timezone',
        'email_notification',
        'email_reminder',
        'department_id',
        'position_id',
        'salary',
        'hire_date',
        'employment_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at' => DateCast::class,
            'salary' => 'decimal:2',
            'hire_date' => 'date',
        ];
    }

    public array $defaultValues = [
        'employment_status' => EmploymentStatus::ACTIVE,
    ];

    public function setFileConfigName(): string
    {
        return self::getClassName();
    }

    public function files(?string $fieldName = null, ?string $fileType = null): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')
            ->when($fieldName, function ($query) use ($fieldName) {
                $query->where('field_name', $fieldName);
            })
            ->when($fileType, function ($query) use ($fileType) {
                $query->where('file_type', $fileType);
            });
    }
}
