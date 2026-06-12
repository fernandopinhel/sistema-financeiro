@extends(Auth::check() ? 'layouts.app' : 'layouts.guest')

@section('title', 'Privacidade')

@section('content')
    @include('politicas.conteudo')
@endsection