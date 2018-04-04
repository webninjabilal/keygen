@extends('layouts.app')
@section('extrastyles')
    <link href="{{ asset('/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <style>
        .padding05{
            padding: 5px;
        }
        table th{
             font-size: 16px;
             font-weight: bolder;
         }
    </style>
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> User Group Permissions</h2>
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
                        <div class="row">
                            <div class="col-lg-12">
                                {!! Form::open(['novalidate' => 'novalidate','id' => 'form']) !!}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="6" class="text-center">Group Permission</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="2" class="text-center">Module
                                                </th>
                                                <th colspan="5" class="text-center">Permission</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Add</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                                <th class="text-center" style="width: 60%;">Misc</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $modules = \App\Role::modules(); ?>
                                        @if(count($modules) > 0)
                                            @foreach($modules AS $prefix => $display_name)
                                                <?php $basic_permissions = \App\Role::permission_basic();?>
                                                @if(count($basic_permissions) > 0)
                                                    <tr>
                                                        <th>{!! $display_name !!}</th>
                                                    @foreach($basic_permissions AS $key => $basic_permission)
                                                        <?php
                                                        $permission = \App\Permission::where('name', $prefix.'-'.$key)->first();
                                                        if(!$permission) {
                                                            $permission  = new \App\Permission();
                                                            $permission->name = $prefix.'-'.$key;
                                                            $permission->display_name = ucfirst($prefix).'-'.ucfirst($basic_permission);
                                                            $permission->save();
                                                        }
                                                        ?>
                                                            <td class="text-center">
                                                                <input type="checkbox" value="{{ $permission->id }}" {{ (in_array($permission->id,$attach_permission)) ? 'checked' : '' }} class="checkbox" name="permission[]" >
                                                            </td>

                                                    @endforeach
                                                        <?php
                                                            $advance_func = $prefix.'_permission_advance';
                                                            $role = new \App\Role();
                                                            $advance_permissions = [];
                                                            if(method_exists($role, $advance_func))
                                                                $advance_permissions = \App\Role::$advance_func();
                                                        ?>
                                                        @if(count($advance_permissions) > 0)
                                                            <td>
                                                                @foreach($advance_permissions AS $key => $advance_permission)
                                                                    <?php
                                                                    $permission = \App\Permission::where('name', $key)->first();
                                                                    if(!$permission) {
                                                                        $permission  = new \App\Permission();
                                                                        $permission->name = $key;
                                                                        $permission->display_name = $advance_permission;
                                                                        $permission->save();
                                                                    }
                                                                    ?>
                                                                    <input type="checkbox" value="{{ $permission->id }}" {{ (in_array($permission->id, $attach_permission)) ? 'checked' : '' }} class="checkbox" name="permission[]" >
                                                                    <label for="{{ $key }}" class="padding05">{{ $advance_permission }}</label>
                                                                @endforeach
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.checkbox').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });
        });
    </script>

@endsection
