@extends('layouts.app')
@section('extrastyles')

@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> User Roles
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_user_role"><i class="fa fa-plus"></i> Add User Role</a>
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
                        <table id="user_role_list" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php $user_role = new \App\Role(); ?>
    <div class="modal inmodal fade" id="create_user_role" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveUserRole();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add User Role</h4>
                </div>
                <div class="modal-body">
                    @include('user-role._form')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="update_user_role" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateUserRole();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update User Role</h4>
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
            var oTable = $('#user_role_list').dataTable({
                "oLanguage": { "sSearch": "" } ,
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax : "{{ route('user_role_records') }}"
            });
        });
        function saveUserRole() {
            var validate = customValidations('create_user_role');
            if(!validate){
                return false;
            }
            return reloadAjaxSubmit('create_user_role',"{{ route('user-role.store') }}",'name','Submit');
        }
        function deleteUserRole(userRoleId) {
            if(confirm('Are you sure you would like to delete it?')) {
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('user-role.destroy', $user_role)}}/'+userRoleId,
                    data: 'user_role_id='+userRoleId+'&_token='+csrf_token,
                }).done(function() {
                    toastrShow('User Role deleted Successfully!','Success');
                    location.reload();
                });
            }
        }
        function getUserRole(userRoleId)
        {
            $.ajax({
                method: 'GET',
                url: '{{ url('user-role')}}/'+userRoleId
            }).done(function(data) {
                $('#update_user_role .modal-body').html(data);
                $('#update_user_role').modal('show');
            });
        }
        function updateUserRole()
        {
            var validate = customValidations('update_user_role');
            if(!validate){
                return false;
            }
            var user_role_id = $('#update_user_role').find('input[name=user_role_id]').val();
            return reloadAjaxSubmit('update_user_role',"{{ url('user-role') }}/"+user_role_id,'name','Update');
        }

        function customValidations(parent_id) {
            var name = $('#'+parent_id+' input[name=name]');

            if(name.val() == '') {
                name.focus();
                return false;
            }
            return true;
        }
    </script>
@endsection
