<?php

namespace poldixd\QueueClearAll\Commands;

use Illuminate\Console\Command;

class QueueClearAllCommand extends Command
{
    protected $signature = 'queue:clear-all
        {connection? : The queue connection to clear. Defaults to the configured queue.default connection.}';

    protected $description = 'Clear all queues configured in Laravel Horizon.';

    public function handle(): int
    {
        $connection = $this->argument('connection') ?? config('queue.default');

        $queues = collect(config('horizon.environments', []))
            ->flatMap(fn (array $environment): array => collect($environment)->pluck('queue')->all())
            ->flatten()
            ->unique()
            ->values()
            ->all();

        foreach ($queues as $queue) {
            $this->call('queue:clear', [
                'connection' => $connection,
                '--queue' => $queue,
            ]);
        }

        return self::SUCCESS;
    }
}
