@extends('layouts.app')
@section('extrastyles')

@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page_heading"> Machines
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_machine"><i class="fa fa-plus"></i> Add Machine</a>
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
                        <table id="machine_list" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Prefix</th>
                                   {{-- <th>Type</th>--}}
                                    {{--<th>Sheet</th>--}}
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
    <?php $machine = new \App\Machine(); ?>
    <div class="modal inmodal fade" id="create_machine" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveMachine();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Machine</h4>
                </div>
                <div class="modal-body">
                    @include('machine._form',compact('machine'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="update_machine" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateMachine();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Machine</h4>
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
            var oTable = $('#machine_list').dataTable({
                "oLanguage": { "sSearch": "" } ,
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                bSort: true,
                ajax : "{{ route('machine_records') }}"
            });
        });
        function saveMachine() {
            var validate = customValidations('create_machine');
            if(!validate){
                return false;
            }
            return reloadAjaxSubmit('create_machine',"{{ route('machine.store') }}",'nick_name','Submit');
        }
        function deleteMachine(machineId) {
            if(confirm('Are you sure you would like to delete it?')) {
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('machine.destroy', $machine)}}/'+machineId,
                    data: 'machine_id='+machineId+'&_token='+csrf_token,
                }).done(function() {
                    toastrShow('Machine deleted Successfully!','Success');
                    location.reload();
                });
            }
        }
        function getMachine(machineId) {
            $.ajax({
                method: 'GET',
                url: '{{ url('machine')}}/'+machineId
            }).done(function(data) {
                $('#update_machine .modal-body').html(data);
                $('#update_machine').modal('show');
            });
        }
        function updateMachine() {
            var validate = customValidations('update_machine');
            if(!validate){
                return false;
            }
            var machine_id = $('#update_machine').find('input[name=machine_id]').val();
            return reloadAjaxSubmit('update_machine',"{{ url('machine') }}/"+machine_id,'nick_name','Update');
        }

        function customValidations(parent_id) {
            var name = $('#'+parent_id+' input[name=name]');
            var sku = $('#'+parent_id+' input[name=sku]');

            if(name.val() == '') {
                name.focus();
                return false;
            } else if(sku.val() == '') {
                sku.focus();
                return false;
            }
            return true;
        }
    </script>
@endsection
