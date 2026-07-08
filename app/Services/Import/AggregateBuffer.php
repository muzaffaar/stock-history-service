<?php

namespace App\Services\Import;
use Illuminate\Support\Facades\Log;
use Throwable;

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

        $rows = $this->buffer;
        $this->buffer = [];

        try {
            $this->bulkInsertService->insert($rows);
            return;
        } catch (Throwable $e) {
            Log::error('Bulk insert failed. Falling back to row-by-row inserts.', [
                'rows' => count($rows),
                'error' => $e->getMessage(),
            ]);
        }

        $failedRows = 0;

        foreach ($rows as $row) {
            try {
                $this->bulkInsertService->insert([$row]);
            } catch (Throwable $e) {
                $failedRows++;

                Log::error('Failed to insert aggregate row.', [
                    'ticker' => $row['ticker'] ?? null,
                    'minute' => $row['minute'] ?? null,
                    'row' => $row,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($failedRows > 0) {
            Log::warning('Batch completed with failed rows.', [
                'batch_size' => count($rows),
                'failed_rows' => $failedRows,
            ]);
        }
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
