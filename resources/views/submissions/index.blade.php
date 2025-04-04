<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissões Recebidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Submissões Recebidas</h1>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Formulário</th>
                        <th>Dados</th>
                        <th>Recebido em</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                    <tr>
                        <td>{{ $submission->id }}</td>
                        <td>{{ $submission->form_name }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#collapseData{{ $submission->id }}">
                                Ver Dados
                            </button>
                            <div class="collapse mt-2" id="collapseData{{ $submission->id }}">
                                <pre class="bg-light p-2">@json($submission->form_data, JSON_PRETTY_PRINT)</pre>                            </div>
                        </td>
                        <td>{{ $submission->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $submissions->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>