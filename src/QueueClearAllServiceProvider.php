<?php

namespace poldixd\QueueClearAll;

use Illuminate\Support\ServiceProvider;
use poldixd\QueueClearAll\Commands\QueueClearAllCommand;

class QueueClearAllServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                QueueClearAllCommand::class,
            ]);
        }
    }
}
