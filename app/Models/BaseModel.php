<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    protected static function booted(): void
    {
        static::creating(function ($model) {
            // If model has user_id column and it's not already set
            if (Auth::check() && !$model->user_id && $model->isFillable('user_id')) {
                $model->user_id = Auth::id();
            }
        });
    }
}
