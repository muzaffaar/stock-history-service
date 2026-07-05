<?php

namespace App\Services\Import;

use App\Services\Parser\AggregateParser;

class MarketDataImporter
{
    public function __construct(
        private readonly AggregateParser $parser,
        private readonly AggregateBuffer $buffer,
    ) {}

    /**
     * Import one websocket aggregate message.
     */
    public function import(array $message): void
    {
        $row = $this->parser->parse($message);

        if ($row === null) {
            return;
        }

        $this->buffer->add($row);
    }

    /**
     * Flush remaining buffered rows.
     */
    public function flush(): void
    {
        $this->buffer->flush();
    }
}
