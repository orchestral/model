<?php

namespace Orchestra\Model\Plugins;

use Illuminate\Database\Eloquent\Model;

trait RefreshOnCreate
{
    /**
     * Boot the refresh on create trait for a model.
     */
    public static function bootRefreshOnCreate(): void
    {
        static::created(static function ($model) {
            $model->refresh();
        });
    }
}
