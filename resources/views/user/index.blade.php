@extends('layouts.app')
@section('extrastyles')
    <link href="{{ asset('/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> Customer
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_user"><i class="fa fa-plus"></i> Add Customer</a>
                    </div>
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
                        <div class="clearfix"></div>
                        <table id="user_list" class="table table-striped table-bordered table-hover" >
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
    <?php $user = new \App\User(); ?>
    <div class="modal inmodal fade" id="create_user" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveUser();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Customer</h4>
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
                    <h4 class="modal-title">Update Customer</h4>
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
    <script type="text/javascript" src="{{ asset('/plugins/dataTables/datatables.min.js') }}"></script>
    @include('user._basic_script')
@endsection
