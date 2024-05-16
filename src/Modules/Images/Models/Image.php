<?php

declare(strict_types=1);

namespace Modules\Images\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'name_scaled',
    ];
}
