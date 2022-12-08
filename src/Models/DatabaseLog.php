<?php

namespace Yesccx\DatabaseLogger\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /*
     * @var array
     */
    protected $casts = [
        'meta_data' => 'array',
    ];
}
