<?php

namespace App\Models\Traits\Scope;

/**
 * Class ProductScope.
 */
trait ProductScope
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

    public function scopePromotion($query, $is_promotion = 1)
    {
        return $query->where('is_promotion', $is_promotion);
    }
}
