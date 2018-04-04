<?php $onlineUser = Auth::user(); ?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header" style="padding-left: 0; padding-right: 0; padding-bottom: 15px">
                <a href="{{ url('') }}" style="padding-left: 8px; padding-right: 0;">
                    <img src="" style="width: 100%;">
                </a>
            </li>
            @if(Auth::user()->isAdmin())
                <li class="{{ (Request::is('user*')) ? ' active' : '' }}">
                    <a href="{{ route('user.index') }}"><i class="fa fa-user" aria-hidden="true"></i>
                        <span class="nav-label"> Users </span>
                    </a>
                </li>
                <li class="{{ (Request::is('sheet*')) ? ' active' : '' }}">
                    <a href="{{ route('sheet.index') }}"><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        <span class="nav-label">Manage Sheets </span>
                    </a>
                </li>
                <li class="{{ (Request::is('unit*')) ? ' active' : '' }}">
                    <a href="{{ route('unit.index') }}"><i class="fa fa-gear" aria-hidden="true"></i>
                        <span class="nav-label"> Units </span>
                    </a>
                </li>
                <li class="{{ (Request::is('machine*')) ? ' active' : '' }}">
                    <a href="{{ route('machine.index') }}"><i class="fa fa-gear" aria-hidden="true"></i>
                        <span class="nav-label"> Machines </span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->isCustomer())
                <li class="{{ (Request::is('my-account')) ? ' active' : '' }}">
                    <a href="{{ url('my-account') }}">
                        <i class="fa fa-user"></i> My Account
                    </a>
                </li>
                <li class="{{ (Request::is('purchase-unit*')) ? ' active' : '' }}">
                    <a href="{{ url('purchase-unit') }}"><i class="fa fa-gear" aria-hidden="true"></i>
                        <span class="nav-label"> Purchase Units </span>
                    </a>
                </li>
            @endif
        </ul>

    </div>
</nav>