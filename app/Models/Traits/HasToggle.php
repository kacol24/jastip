<?php

namespace App\Models\Traits;

trait HasToggle
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', true);
    }
}
