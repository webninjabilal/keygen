<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <div class="navbar-header">

        </div>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li>{!! $companies_dropdown !!}</li>
        <li>
            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                <i class="fa fa-user-secret"></i> {{ Auth::user()->full_name }} <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                @if(Auth::user()->isAdmin())
                <li>
                    <a href="{{ url('my-account') }}">
                        <i class="fa fa-user"></i> My Account
                    </a>
                </li>

                <li>
                    <a href="{{ url('user-role') }}">
                        <i class="fa fa-sliders"></i> Roles
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ url('auth/logout') }}">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>