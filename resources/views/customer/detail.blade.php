@extends('layouts.app')
@section('extrastyles')

@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> {{ $customer->name }}
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
                                    <ul class="nav nav-tabs">
                                        <li>
                                            <a  data-toggle="tab" data-id="my-codes" href="#tab-codes">Codes</a>
                                        </li>
                                        <li class="active">
                                            <a  data-toggle="tab" data-id="my-users" href="#tab-users">Users</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="tab-codes" class="tab-pane">
                                            <div class="panel-body">
                                                @include('customer.machine')
                                            </div>
                                        </div>
                                        <div id="tab-users" class="tab-pane active">
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
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php $user = new \App\User(); ?>
    <div class="modal inmodal fade" id="create_user" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveUser();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add User</h4>
                </div>
                <div class="modal-body">
                    @include('user.form._form',compact('user'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="update_user" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateUser();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update User</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
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

    </script>
    @include('user._basic_script')
@endsection
