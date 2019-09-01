@extends('base')

@section('content')
<div class="container">
    <div class="row margin-top-30 justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">¡Bienvenido a SIESTA!</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Estás logueado correctamente :)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
