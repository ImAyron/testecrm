@extends('layouts.admin')

@section('page_title', 'Detalhes do Lead')

@section('main_content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" 
                         src="{{ asset('img/user-profile-default.png') }}" 
                         alt="Lead profile">
                </div>
                
                <h3 class="profile-username text-center" id="lead-name"></h3>
                <p class="text-muted text-center" id="lead-form"></p>
                
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <span class="float-right text-info" id="lead-email"></span>
                    </li>
                    <li class="list-group-item">
                        <b>Telefone</b> <span class="float-right text-info" id="lead-phone"></span>
                    </li>
                    <li class="list-group-item">
                        <b>Data</b> <span class="float-right text-info" id="lead-date"></span>
                    </li>
                </ul>
                
                <div class="mt-4">
                    <h5>Dados Completos</h5>
                    <pre class="bg-light p-3 rounded" id="lead-full-data"></pre>
                </div>
                
                <a href="{{ route('leads.index') }}" class="btn btn-primary btn-block mt-3">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    const leadId = window.location.pathname.split('/').pop();
    
    $.get(`/admin/leads/${leadId}`, function(response) {
        if(response.success) {
            const lead = response.data.submission;
            const formData = response.data.form_data;
            
            $('#lead-name').text(formData.name || 'N/A');
            $('#lead-form').text(lead.form_name);
            $('#lead-email').text(formData.email || 'N/A');
            $('#lead-phone').text(formData.phone || 'N/A');
            $('#lead-date').text(response.data.created_at_formatted);
            $('#lead-full-data').text(JSON.stringify(formData, null, 2));
        }
    }).fail(function() {
        alert('Erro ao carregar detalhes do lead');
        window.location.href = "{{ route('leads.index') }}";
    });
});
</script>
@endsection