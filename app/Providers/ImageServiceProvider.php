<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Images\Interfaces\ImageService;
use Modules\Images\Services\ImageServiceImpl;

class ImageServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ImageService::class => ImageServiceImpl::class,
    ];
}
