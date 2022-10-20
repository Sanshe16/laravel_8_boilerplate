<?php

namespace App\Models\Traits\Method;

/**
 * Trait ProductMethod.
 */
trait ProductMethod
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active == 'active' ? true : false ;
    }

    /**
     * @return bool
     */
    public function isPromotion()
    {
        return $this->is_promotion == 1 ? true : false ;
    }
    

}
