{{-- resources/views/admin/submissions/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Formulários Recebidos')

@section('content_header')
    <h1>Formulários Recebidos</h1>
@stop

@section('content')
    @if($submissions->isEmpty())
        <p>Nenhum formulário foi enviado ainda.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Formulário</th>
                    <th>Dados</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>{{ $submission->id }}</td>
                        <td>{{ $submission->form_name }}</td>
                        <td>
                            <pre>{{ json_encode(json_decode($submission->form_data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </td>
                        <td>{{ $submission->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@stop
