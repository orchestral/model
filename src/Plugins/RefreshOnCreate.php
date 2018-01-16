<?php

namespace Orchestra\Model\Plugins;

use Illuminate\Database\Eloquent\Model;

trait RefreshOnCreate
{
    /**
     * Boot the refresh on create trait for a model.
     *
     * @return void
     */
    public static function bootRefreshOnCreate(): void
    {
        static::created(function ($model) {
            $model->refresh();
        });
    }
}
