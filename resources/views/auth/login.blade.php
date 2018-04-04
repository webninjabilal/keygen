@extends('auth_app')

@section('extrastyles')
    <script>
        function trimInput() {
            var value = document.querySelector('[name="email"]').value;
            document.querySelector('[name="email"]').value = value.trim();
        }
    </script>
@endsection

@section('content')
    <div class="passwordBox animated fadeInDown">
        <div>
            <div>
                <div class="logo-element" style="display: block;">
                    <img src="{{ asset('/images/logo.png') }}">
                </div>
            </div>
            <div class="ibox-content">
                <h2 class="font-bold">Login</h2>
                <p>Login in. To see it in action.</p>
                <form class="m-t" name="loginform"  role="form" method="POST" action="{{ url('login') }}">
                    @include('errors._form')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <input type="text" class="form-control" onkeydown="trimInput()" name="email" value="{{ old('email') }}"  required="" placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" required="" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                    <a href="{{ route('password.request') }}"><small>Forgot password?</small></a>
                </form>
            </div>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-12 text-center">
                © {{ date('Y') }}, KeyGen. All Rights Reserved
            </div>
        </div>
    </div>
@endsection
