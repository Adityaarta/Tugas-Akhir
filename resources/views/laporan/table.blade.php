@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $title }}</h4>
    <button class="btn btn-primary btn-sm" onclick="window.print()">
        <i class="fa fa-print me-1"></i> Cetak
    </button>
</div>

<div class="card card-box p-3">
    <table class="table table-hover align-middle datatable">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                @foreach($row as $cell)
                    <td>{{ $cell }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
