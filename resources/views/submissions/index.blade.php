@extends('adminlte::page')

@section('title', 'Leads Recebidos')

@section('content_header')
    <h1 class="text-center">Leads Recebidos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Lista de Leads</h3>
                    <div class="card-tools">
                        <a href="{{ route('leads.export') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-export"></i> Exportar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Formulário</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                @php
                                    $formData = json_decode($submission->form_data, true);
                                @endphp
                                <tr>
                                    <td>{{ $submission->id }}</td>
                                    <td>{{ $formData['name'] ?? 'N/A' }}</td>
                                    <td>{{ $formData['email'] ?? 'N/A' }}</td>
                                    <td>{{ $formData['phone'] ?? 'N/A' }}</td>
                                    <td>{{ $submission->form_name }}</td>
                                    <td>{{ $submission->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('leads.show', $submission->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('leads.destroy', $submission->id) }}" 
                                              method="POST" 
                                              style="display: inline-block;"
                                              onsubmit="return confirm('Confirma a exclusão deste lead?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    {{ $submissions->links('pagination::bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalhes -->
    <div class="modal fade" id="leadDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Lead</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img class="img-fluid rounded-circle" 
                             src="/img/lead-icon.png" 
                             alt="Lead" 
                             style="width: 100px; height: 100px;">
                    </div>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>ID</b> <span class="float-right text-info">{{ $submission->id ?? '' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Nome</b> <span class="float-right text-info">{{ $formData['name'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <span class="float-right text-info">{{ $formData['email'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Telefone</b> <span class="float-right text-info">{{ $formData['phone'] ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Formulário</b> <span class="float-right text-info">{{ $submission->form_name ?? '' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Data</b> <span class="float-right text-info">
                                {{ $submission->created_at->format('d/m/Y H:i:s') ?? '' }}
                            </span>
                        </li>
                        @if(isset($formData['utm_source']))
                        <li class="list-group-item">
                            <b>UTM Source</b> <span class="float-right text-info">{{ $formData['utm_source'] }}</span>
                        </li>
                        @endif
                    </ul>
                    
                    <div class="mt-3">
                        <h5>Dados Completos</h5>
                        <pre class="bg-light p-3 rounded">{{ json_encode($formData, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        
        .profile-user-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .table-responsive {
            overflow-x: auto;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Abre modal com detalhes quando clicar em ver
            $('a[href*="leads/show"]').click(function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                
                $.get(url, function(data) {
                    if(data.success) {
                        // Preenche o modal com os dados
                        $('#leadDetailsModal .modal-title').text('Detalhes do Lead #' + data.data.submission.id);
                        $('#leadDetailsModal .list-group-item span').text('');
                        
                        // Preenche os campos básicos
                        $('#leadDetailsModal .list-group-item:nth-child(1) span').text(data.data.submission.id);
                        $('#leadDetailsModal .list-group-item:nth-child(2) span').text(data.data.form_data.name || 'N/A');
                        $('#leadDetailsModal .list-group-item:nth-child(3) span').text(data.data.form_data.email || 'N/A');
                        $('#leadDetailsModal .list-group-item:nth-child(4) span').text(data.data.form_data.phone || 'N/A');
                        $('#leadDetailsModal .list-group-item:nth-child(5) span').text(data.data.submission.form_name);
                        $('#leadDetailsModal .list-group-item:nth-child(6) span').text(
                            new Date(data.data.submission.created_at).toLocaleString('pt-BR')
                        );
                        
                        // Preenche UTMs se existirem
                        if(data.data.form_data.utm_source) {
                            $('#leadDetailsModal .list-group-item:nth-child(7) span').text(data.data.form_data.utm_source);
                        }
                        
                        // Preenche o JSON completo
                        $('#leadDetailsModal pre').text(JSON.stringify(data.data.form_data, null, 2));
                        
                        // Mostra o modal
                        $('#leadDetailsModal').modal('show');
                    }
                }).fail(function() {
                    alert('Erro ao carregar detalhes do lead');
                });
            });
        });
    </script>
@stop