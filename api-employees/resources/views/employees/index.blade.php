<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Importar CSV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
</head>
<body>

    <div class="container">
        <div class="card my-3 border-light shadow-lg">
            <div class="card-header">
                <h2>Importar CSV</h2>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{!! session('success') !!}</div>
                @endif

                @if (session('error'))
                        <div class="alert alert-danger" role="alert">{!! session('error') !!}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('employee.import') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="input-group my-3">
                        <input type="file" name="file" class="form-control" id="file" accept=".csv">
                        <button type="submit" class="btn btn-outline-success" id="fileButton">
                            <i class="fa-solid fa-file-import"></i>Importar
                        </button>
                    </div>
                </form>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">CPF</th>
                            <th scope="col">Cidade</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->cpf }}</td>
                                <td>{{ $employee->city }}</td>
                                <td>{{ $employee->state }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
