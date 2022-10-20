<?php

namespace App\Models\Traits\Method;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 'active' ? true : false ;
    }

}
