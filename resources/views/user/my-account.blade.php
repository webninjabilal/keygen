@extends('layouts.app')
@section('extrastyles')

@endsection
<?php $is_my_account = true; ?>
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> {{ $user->full_name }}
                </h2>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content p-md">
                        @include('flash::message')
                        @include('errors._form')
                        <div class="clearfix"></div>
                        <div class="row" id="my_account">
                            <div class="col-sm-12">
                                @if($user->isCustomer())
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a  data-toggle="tab" data-id="my-profile" href="#tab-my-account">My Account</a>
                                    </li>
                                    <li>
                                        <a  data-toggle="tab" data-id="my-machines" href="#tab-machines">Machines</a>
                                    </li>
                                    <li>
                                        <a  data-toggle="tab" data-id="my-orders" href="#tab-orders">Orders</a>
                                    </li>
                                    <li>
                                        <a  data-toggle="tab" data-id="my-users" href="#tab-users">Users</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab-my-account" class="tab-pane active">
                                        <div class="panel-body">
                                @endif
                                                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateMyAccount();']) !!}
                                                @include('user.form._form', compact('user'))
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                                {!! Form::close() !!}
                                @if($user->isCustomer())
                                        </div>
                                    </div>
                                    <div id="tab-machines" class="tab-pane">
                                        <div class="panel-body">
                                            @include('user.machine')
                                        </div>
                                    </div>
                                    <div id="tab-orders" class="tab-pane">
                                        <div class="panel-body">
                                            @include('user.orders')
                                        </div>
                                    </div>
                                    <div id="tab-users" class="tab-pane">
                                        <div class="panel-body">
                                            @include('flash::message')
                                            <div class="clearfix"></div>
                                                <div class="pull-right">
                                                    <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_user"><i class="fa fa-plus"></i> Add User</a>
                                                </div>
                                                <div class="clearfix"></div>
                                                <table id="user_list" class="table table-striped table-bordered table-hover" style="width: 100%;" >
                                                    <thead>
                                                    <tr>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Email Address</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('user._popup')
@endsection

@section('footer')

    <script>
        $(document).ready(function () {
            var hash = window.location.hash;
            hash = hash.replace('#','');
            hash = hash.split('/');
            if(hash[0] != ''){
                var parentNav = $('#tab-'+hash[0]).closest('.parent_tab').attr('id');
                if(parentNav != undefined && parentNav != '') {
                    $('.nav-tabs a[href="#' + parentNav + '"]').tab('show');
                }
                $('.nav-tabs a[href="#tab-' + hash[0] + '"]').tab('show');
            }
            $('.nav-tabs a').click(function (e) {
                var hasShow = this.hash;
                hasShow = hasShow.replace('tab-','');
                window.location.hash = hasShow;
            });
        });
        function updateMyAccount() {

            var validate = customValidations('my_account');
            if(!validate){
                return false;
            }

            var user_id = $('#tab-my-account form').find('input[name=user_id]').val();
            return reloadAjaxSubmit('my_account',"{{ url('user') }}/"+user_id,'name','Update');
        }

        function customValidations(parent_id) {
            var company = $('#'+parent_id+' input[name=company]');
            var first_name = $('#'+parent_id+' input[name=first_name]');
            var last_name = $('#'+parent_id+' input[name=last_name]');
            var phone = $('#'+parent_id+' input[name=phone]');
            var email = $('#'+parent_id+' input[name=email]');

            if(company.val() == '') {
                company.focus();
                return false;
            } else if(first_name.val() == '') {
                first_name.focus();
                return false;
            } else if(last_name.val() == '') {
                last_name.focus();
                return false;
            } else if(phone.val() == '') {
                phone.focus();
                return false;
            } else if(email.val() == '') {
                email.focus();
                return false;
            }
            return true;
        }
    </script>
    @include('user._script')
    @include('user._basic_script')
@endsection
