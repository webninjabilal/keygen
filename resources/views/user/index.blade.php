@extends('layouts.app')
@section('extrastyles')
    <link href="{{ asset('/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> User
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_user"><i class="fa fa-plus"></i> Add User</a>
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
    <script type="text/javascript" src="{{ asset('/plugins/dataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var oTable = $('#user_list').dataTable({
                "oLanguage": { "sSearch": "" } ,
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                bSort: false,
                ajax : "{{ route('user_records') }}"
            });
        });
        function saveUser() {
            var validate = customValidations('create_user');
            if(!validate){
                return false;
            }
            return reloadAjaxSubmit('create_user',"{{ route('user.store') }}",'name','Submit');
        }
        function deleteUser(userId) {
            if(confirm('Are you sure you would like to delete it?')) {
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('user.destroy', $user)}}/'+userId,
                    data: 'user_id='+userId+'&_token='+csrf_token,
                }).done(function() {
                    toastrShow('User deleted Successfully!','Success');
                    location.reload();
                });
            }
        }
        function getUser(userId) {
            $.ajax({
                method: 'GET',
                url: '{{ url('user')}}/'+userId
            }).done(function(data) {
                $('#update_user .modal-body').html(data);
                $('#update_user').modal('show');
            });
        }
        function updateUser() {
            var validate = customValidations('update_user');
            if(!validate){
                return false;
            }
            var user_id = $('#update_user').find('input[name=user_id]').val();
            return reloadAjaxSubmit('update_user',"{{ url('user') }}/"+user_id,'name','Update');
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
@endsection
