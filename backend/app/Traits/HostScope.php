<?php

namespace App\Traits;

use App\Scopes\HostScope as Scope;

trait HostScope
{
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new Scope);
    }
}
