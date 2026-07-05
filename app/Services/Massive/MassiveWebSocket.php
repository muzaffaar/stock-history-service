<?php

namespace App\Services\Massive;

use App\Services\Import\MarketDataImporter;
use App\Services\Monitoring\MonitoringService;
use App\Services\Import\AggregateBuffer;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use React\EventLoop\Loop;
use Throwable;

class MassiveWebSocket
{
    private ?WebSocket $connection = null;

    public function __construct(
        private readonly MarketDataImporter $importer,
        private readonly AggregateBuffer $buffer,
        private readonly CollectorMetrics $metrics,
        private readonly MonitoringService $monitor,
    ) {}

    public function run(): void
    {
        $this->metrics->start();

        $this->connect();

        $this->startMonitoring();

        $this->startDashboard();

        $this->startHeartbeat();

        Loop::get()->run();

    }

    private function connect(): void
    {
        $connector = new Connector(Loop::get());

        $connector(config('history.ws_url'))
            ->then(
                fn (WebSocket $conn) => $this->onConnected($conn),
                fn (Throwable $e) => $this->onConnectionFailed($e)
            );
    }

    private function onConnected(WebSocket $connection): void
    {
        $this->connection = $connection;

        logger()->info('Connected to Massive');

        $this->authenticate();

        $this->subscribe();

        $this->listen();
    }

    private function onConnectionFailed(Throwable $e): void
    {
        logger()->error($e->getMessage());

        $this->reconnect();
    }

    private function authenticate(): void
    {
        $this->send([
            "action" => "auth",
            "params" => config('history.api_key'),
        ]);
    }

    private function subscribe(): void
    {
        $this->send([
            "action" => "subscribe",
            "params" => implode(',', config('history.subscriptions')),
        ]);
    }

    private function listen(): void
    {
        $this->connection->on('message', function ($message) {

            $payload = json_decode((string) $message, true);

            dump($payload);

            foreach ($payload as $event) {
                $this->metrics->message();
                $this->handleMessage($event);
            }

        });

        $this->connection->on('close', function () {

            logger()->warning('WebSocket closed');

            $this->importer->flush();

            $this->reconnect();

        });
    }

    private function handleMessage(array $message): void
    {
        if (($message['ev'] ?? null) !== 'AM') {
            return;
        }
        $this->metrics->aggregate();
        $this->importer->import($message);
    }

    private function reconnect(): void
    {
        $this->metrics->reconnect();
        logger()->info('Reconnect in 5 seconds...');

        Loop::addTimer(5, function () {
            $this->connect();
        });
    }

    private function send(array $payload): void
    {
        $this->connection?->send(
            json_encode($payload)
        );
    }

    private function startDashboard(): void
    {
        Loop::addPeriodicTimer(30, function () {

            $stats = $this->metrics->status();

            $this->printDashboard($stats);

        });
    }

    private function printDashboard(array $stats): void
    {
        echo PHP_EOL;

        echo "==========================================" . PHP_EOL;

        echo " History Collector" . PHP_EOL;

        echo "==========================================" . PHP_EOL;

        echo "Messages      : {$stats['messages']}" . PHP_EOL;

        echo "Aggregates    : {$stats['aggregates']}" . PHP_EOL;

        echo "Inserted      : {$stats['inserted']}" . PHP_EOL;

        echo "Buffered      : {$this->buffer->count()}" . PHP_EOL;

        echo "Reconnects    : {$stats['reconnects']}" . PHP_EOL;

        echo "Memory        : {$stats['memory']}" . PHP_EOL;

        echo "Uptime        : {$stats['uptime']}" . PHP_EOL;

        echo "Last Message  : {$stats['last_message']}" . PHP_EOL;

        echo "==========================================" . PHP_EOL;
    }

    private function startHeartbeat(): void
    {
        Loop::addPeriodicTimer(30, function () {

            if ($this->metrics->lastMessageAt === null) {
                return;
            }

            if (
                now()->diffInSeconds(
                    $this->metrics->lastMessageAt
                ) > 60
            ) {

                echo "Heartbeat timeout. Reconnecting..." . PHP_EOL;

                $this->connection?->close();

            }

        });
    }

    private function startMonitoring(): void
    {
        Loop::addPeriodicTimer(2, function () {

            $this->monitor->publish();

        });
    }
}
