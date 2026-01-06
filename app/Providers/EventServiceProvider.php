<?php

use App\Listeners\MergeCartListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServicesProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
    Login::class => [
        MergeCartListener::class,
    ],
];
}