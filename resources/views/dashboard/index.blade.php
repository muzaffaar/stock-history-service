@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">
        Scanner Dashboard
    </h2>

    {{-- Live Status --}}
    <div class="row mb-4">

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    Live Status
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <strong>Connection</strong><br>

                            @if(data_get($data,'live.connected'))
                                <span class="badge bg-success">Connected</span>
                            @else
                                <span class="badge bg-danger">Disconnected</span>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <strong>Market</strong><br>
                            {{ data_get($data,'live.market','-') }}
                        </div>

                        <div class="col-md-3">
                            <strong>Uptime</strong><br>
                            {{ data_get($data,'live.uptime','-') }}
                        </div>

                        <div class="col-md-3">
                            <strong>Memory</strong><br>
                            {{ data_get($data,'live.memory_mb',0) }} MB
                        </div>

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-2">
                            <strong>Messages</strong><br>
                            {{ number_format(data_get($data,'live.messages',0)) }}
                        </div>

                        <div class="col-md-2">
                            <strong>Aggregates</strong><br>
                            {{ number_format(data_get($data,'live.aggregates',0)) }}
                        </div>

                        <div class="col-md-2">
                            <strong>Inserted</strong><br>
                            {{ number_format(data_get($data,'live.inserted',0)) }}
                        </div>

                        <div class="col-md-2">
                            <strong>Buffered</strong><br>
                            {{ number_format(data_get($data,'live.buffered',0)) }}
                        </div>

                        <div class="col-md-2">
                            <strong>Reconnects</strong><br>
                            {{ data_get($data,'live.reconnects',0) }}
                        </div>

                        <div class="col-md-2">
                            <strong>Last Message</strong><br>
                            {{ data_get($data,'live.last_message','-') }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Statistics --}}
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card text-center">

                <div class="card-body">

                    <h6>Rows Today</h6>

                    <h3>{{ number_format(optional($data['today'])->rows_today ?? 0) }}</h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center">

                <div class="card-body">

                    <h6>Total Rows</h6>

                    <h3>{{ number_format(optional($data['today'])->rows_total ?? 0) }}</h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center">

                <div class="card-body">

                    <h6>Tickers</h6>

                    <h3>{{ number_format(optional($data['today'])->ticker_count ?? 0) }}</h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card text-center">

                <div class="card-body">

                    <h6>Database Size</h6>

                    <h3>{{ optional($data['today'])->database_size_mb ?? 0 }} MB</h3>

                </div>

            </div>

        </div>

    </div>

    {{-- Database --}}
    <div class="row mb-4">

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    Database
                </div>

                <table class="table table-bordered table-hover mb-0">

                    <tbody>

                    <tr>

                        <th width="250">Current Partition</th>

                        <td>{{ optional($data['today'])->partition }}</td>

                    </tr>

                    <tr>

                        <th>Messages Received</th>

                        <td>{{ number_format(optional($data['today'])->messages_received ?? 0) }}</td>

                    </tr>

                    <tr>

                        <th>Aggregates Received</th>

                        <td>{{ number_format(optional($data['today'])->aggregates_received ?? 0) }}</td>

                    </tr>

                    <tr>

                        <th>Rows Inserted</th>

                        <td>{{ number_format(optional($data['today'])->rows_inserted ?? 0) }}</td>

                    </tr>

                    <tr>

                        <th>Peak Memory</th>

                        <td>{{ optional($data['today'])->peak_memory_mb ?? 0 }} MB</td>

                    </tr>

                    <tr>

                        <th>Runtime</th>

                        <td>{{ gmdate('H:i:s', optional($data['today'])->runtime_seconds ?? 0) }}</td>

                    </tr>

                </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- History --}}
    <div class="row">

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    Last 30 Days
                </div>

                <table class="table table-striped table-hover mb-0">

                    <thead>

                    <tr>

                        <th>Date</th>
                        <th>Rows Today</th>
                        <th>Total Rows</th>
                        <th>Tickers</th>
                        <th>Database</th>
                        <th>Inserted</th>
                        <th>Messages</th>
                        <th>Reconnects</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($data['history'] as $day)

                        <tr>

                            <td>{{ $day->date }}</td>

                            <td>{{ number_format($day->rows_today) }}</td>

                            <td>{{ number_format($day->rows_total) }}</td>

                            <td>{{ number_format($day->ticker_count) }}</td>

                            <td>{{ $day->database_size_mb }} MB</td>

                            <td>{{ number_format($day->rows_inserted) }}</td>

                            <td>{{ number_format($day->messages_received) }}</td>

                            <td>{{ $day->reconnects }}</td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
