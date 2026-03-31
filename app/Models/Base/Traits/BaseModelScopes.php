<?php

namespace App\Models\Base\Traits;

use App\Models\Base\Enums\ShowStatus;
use Illuminate\Database\Eloquent\Builder;

trait BaseModelScopes
{
    /**
     * Function to join tables.
     */
    public function scopeJoinTo(Builder $query): Builder
    {
        $params = func_get_args()[1];
        $table = $this->getTable();
        $joinTableName = is_array($params) ? $params['t'] : $params;
        $joinTable = app()->make($joinTableName)->getTable();

        return $query->join($joinTable, function ($query) use ($table, $params, $joinTable) {
            $localKey = $params['l_k'] ?? 'id';
            $foreignKey = $params['f_k'] ?? $this->getForeignKey();

            $query->on($joinTable . '.' . $foreignKey, '=', $table . '.' . $localKey);

            if (isset($params['where'])) {
                $query->where($params['where']);
            }
        });
    }

    /**
     * Function to order by sort_order.
     */
    public function scopeOrdered(Builder $query, string $mode = 'ASC'): Builder
    {
        $table = $this->getTable();

        return $query->orderBy($table . '.sort_order', $mode)->orderByDesc($table . '.id');
    }

    /**
     * Function to exclude select data.
     */
    public function scopeExclude(Builder $query, array $excludeColumns = []): Builder
    {
        $selectColumns = array_diff($this->fillable, $excludeColumns);
        $selectColumns[] = 'id';

        return $query->select($selectColumns);
    }

    /**
     * Function to get only active data.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->getTable() . '.show_status', ShowStatus::ACTIVE);
    }
}
