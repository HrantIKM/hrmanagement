<?php

namespace App\Models\Menu;

use App\Models\Base\BaseModel;
use App\Models\Menu\Enums\MenuType;
use App\Models\Scopes\Menu\UserMenuScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Menu extends BaseModel
{
    use HasRoles;

    protected string $guard_name = 'web';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'group_name',
        'url',
        'icon',
        'type',
        'sort_order',
        'show_status',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new UserMenuScope());
    }

    public function scopeAdmin(Builder $builder): void
    {
        $builder->where('type', MenuType::ADMIN);
    }

    public function subMenu(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }
}
