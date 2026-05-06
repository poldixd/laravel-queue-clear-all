<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use poldixd\QueueClearAll\Commands\QueueClearAllCommand;

it('clears every unique queue configured in horizon', function () {
    config()->set('queue.default', 'redis');
    config()->set('horizon.environments', [
        'production' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default', 'emails'],
            ],
            'supervisor-2' => [
                'connection' => 'redis',
                'queue' => ['emails', 'notifications'],
            ],
        ],
        'local' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => 'default',
            ],
        ],
    ]);

    $command = Mockery::mock(QueueClearAllCommand::class)->makePartial();

    $command
        ->shouldReceive('argument')
        ->once()
        ->with('connection')
        ->andReturnNull();

    foreach (['default', 'emails', 'notifications'] as $queue) {
        $command
            ->shouldReceive('call')
            ->once()
            ->with('queue:clear', [
                'connection' => 'redis',
                '--queue' => $queue,
            ])
            ->andReturn(Command::SUCCESS);
    }

    expect($command->handle())->toBe(Command::SUCCESS);
});

it('clears queues on a given connection', function () {
    config()->set('queue.default', 'database');
    config()->set('horizon.environments', [
        'production' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['default'],
            ],
        ],
    ]);

    $command = Mockery::mock(QueueClearAllCommand::class)->makePartial();

    $command
        ->shouldReceive('argument')
        ->once()
        ->with('connection')
        ->andReturn('redis');

    $command
        ->shouldReceive('call')
        ->once()
        ->with('queue:clear', [
            'connection' => 'redis',
            '--queue' => 'default',
        ])
        ->andReturn(Command::SUCCESS);

    expect($command->handle())->toBe(Command::SUCCESS);
});

it('registers the queue clear all command', function () {
    expect(array_key_exists('queue:clear-all', Artisan::all()))->toBeTrue();
});
