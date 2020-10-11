<?php

namespace CodeMaster\CodeAcl;

use CodeMaster\CodeAcl\Events\PermissionSaved;
use CodeMaster\CodeAcl\Listeners\PermissionSaved as ListenersPermissionSaved;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        PermissionSaved::class => [
            ListenersPermissionSaved::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
