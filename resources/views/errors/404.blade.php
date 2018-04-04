@extends('auth_app')

@section('content')
    <div class="middle-box text-center animated fadeInDown">
        <h1>404</h1>
        <h3 class="font-bold">Something unexpected happened</h3>
        <div class="error-desc">
            The server encountered something unexpected that didn't allow it to complete the request. We apologize.<br/>
            Our Engineers get notified we'll fix this issue soon :-) <br />
            You can go back to main page: <br/><a href="{{ url('') }}" class="btn btn-primary m-t">Dashboard</a>
        </div>
    </div>
@endsection