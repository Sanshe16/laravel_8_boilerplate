<?php

namespace App\Models\Traits\Scope;

/**
 * Class UserScope.
 */
trait UserScope
{
    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = 'active')
    {
        return $query->where('status', $status);
    }

    public function scopeWhereContact($query, $phone_number = null)
    {
        if(!is_null($phone_number) && !empty( $phone_number))
        {
            return $query->where('phone_number', $phone_number);
        }
        else
        {
            return $query->where('id', '<' , 0);
        }
    }

    public function scopeGetUserByEmail($query, $email = null)
    {
        return $query->where('email', $email);
    }
}
