<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>EVOCRM - Sistema de Gestão de Relacionamento</title>

	<link rel="icon" href="https://raw.githubusercontent.com/twbs/icons/main/icons/person-lines-fill.svg">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
		integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

	<link rel="stylesheet" href="/css/painel.css" />

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
		integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
		integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
		crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
		integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
		crossorigin="anonymous"></script>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand font-weight-bold" href="{{ route('dashboard') }}">EVOCRM</a>
		<div class="collapse navbar-collapse">
			<ul class="navbar-nav ml-auto">
				@guest
				<li class="nav-item">
					<a class="nav-link" href="{{ route('login') }}">{{ __('Entrar') }}</a>
				</li>
				@if (Route::has('register'))
				<li class="nav-item">
					<a class="nav-link" href="{{ route('register') }}">{{ __('Registrar') }}</a>
				</li>
				@endif
				@else
				<li class="nav-item">
					<a class="nav-link btn btn-success text-white mr-2" href="{{ route('dashboard') }}">Dashboard</a>
				</li>
				<li class="nav-item dropdown">
					<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
						{{ Auth::user()->name }}
					</a>

					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="{{ route('logout') }}"
							onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							{{ __('Logout') }}
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
							@csrf
						</form>
					</div>
				</li>
				@endguest
			</ul>
		</div>
	</nav>

	@if(session('mensagem'))
	<div class="alert alert-success text-center m-4">
		{{ session('mensagem') }}
	</div>
	@endif

	<div class="container mt-5">
		<div class="row align-items-center">
			<div class="col-md-6">
				<h1 class="display-4 font-weight-bold">Bem-vindo ao EVOCRM</h1>
				<p class="lead mt-4 text-justify">
					O EVOCRM é sua solução definitiva para o gerenciamento de relacionamentos com clientes. Automatize processos, organize contatos e aumente sua produtividade em um ambiente moderno e intuitivo.
				</p>
				@guest
				<a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3">Entrar</a>
				<a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg mt-3 ml-2">Registrar</a>
				@endguest
			</div>
			<div class="col-md-6 text-center">
				<img src="/img/principal.jpg" class="img-fluid rounded shadow" alt="CRM dashboard illustration">
			</div>
		</div>
	</div>

	<footer class="text-center text-muted mt-5 mb-3">
		<p>&copy; {{ date('Y') }} EVOCRM - Todos os direitos reservados</p>
	</footer>

</body>

</html>
