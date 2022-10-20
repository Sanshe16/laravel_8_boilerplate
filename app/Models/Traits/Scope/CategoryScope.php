<?php

namespace App\Models\Traits\Scope;

/**
 * Class CategoryScope.
 */
trait CategoryScope
{
    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = 'active')
    {
        return $query->where('is_active', $status);
    }

    public function scopeLevel($query, $level = 0)
    {
        return $query->where('level', $level);
    }
}
