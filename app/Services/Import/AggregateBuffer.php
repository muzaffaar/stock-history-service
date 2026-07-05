<?php

namespace App\Services\Import;

class AggregateBuffer
{
    private array $buffer = [];

    public function __construct(
        private readonly BulkInsertService $bulkInsertService
    ) {}

    public function add(array $row): void
    {
        $this->buffer[] = $row;

        if ($this->shouldFlush()) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        if (empty($this->buffer)) {
            return;
        }

        $this->bulkInsertService->insert($this->buffer);

        $this->buffer = [];
    }

    public function count(): int
    {
        return count($this->buffer);
    }

    private function shouldFlush(): bool
    {
        return $this->count() >= config('history.bulk_insert_size');
    }
}
