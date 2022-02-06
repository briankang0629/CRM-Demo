<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HostScope implements Scope
{
    /**
     * 套用全域 Eloquent 查詢構造器。
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $host = host();
        if (!$host) {
            return;
        }
        return $builder->where('host_id', host()->host_id);
    }
}
