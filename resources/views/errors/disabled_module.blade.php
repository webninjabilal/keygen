@extends('auth_app')

@section('content')
<div class="middle-box text-center animated fadeInDown">
    <h1>Oops!</h1>
    <h3 class="font-bold">Wrong Place</h3>

    <div class="error-desc">
        Something may be hidden from you :-( <br/>
        Go to home page <br/><a href="{{ url('home') }}" class="btn btn-primary m-t">Dashboard</a>
    </div>
</div>
@endsection