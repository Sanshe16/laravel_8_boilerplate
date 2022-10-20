<?php

namespace App\Models\Traits\Method;

use App\Models\Category;

/**
 * Trait CategoryMethod.
 */
trait CategoryMethod
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
    public function isParentMostLevel()
    {
        return $this->level == 0 ? true : false ;
    }

    /**
     * @return model Category
     */

    public function findByslug($slug)
    {
        return Category::where('slug', $slug);
    }

}
