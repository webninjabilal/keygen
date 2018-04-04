@extends('layouts.app')
@section('extrastyles')

@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> Units
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_unit"><i class="fa fa-plus"></i> Add Unit</a>
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
                        <table id="unit_list" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php $unit = new \App\Unit(); ?>
    <div class="modal inmodal fade" id="create_unit" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveUnit();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Unit</h4>
                </div>
                <div class="modal-body">
                    @include('unit._form',compact('user'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="update_unit" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateUnit();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Unit</h4>
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
            var oTable = $('#unit_list').dataTable({
                "oLanguage": { "sSearch": "" } ,
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                bSort: false,
                ajax : "{{ route('unit_records') }}"
            });
        });
        function saveUnit() {
            var validate = customValidations('create_unit');
            if(!validate){
                return false;
            }
            return reloadAjaxSubmit('create_unit',"{{ route('unit.store') }}",'title','Submit');
        }
        function deleteUnit(unitId) {
            if(confirm('Are you sure you would like to delete it?')) {
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('unit.destroy', $unit)}}/'+unitId,
                    data: 'unit_id='+unitId+'&_token='+csrf_token,
                }).done(function() {
                    toastrShow('Unit deleted Successfully!','Success');
                    location.reload();
                });
            }
        }
        function getUnit(unitId) {
            $.ajax({
                method: 'GET',
                url: '{{ url('unit')}}/'+unitId
            }).done(function(data) {
                $('#update_unit .modal-body').html(data);
                $('#update_unit').modal('show');
            });
        }
        function updateUnit() {
            var validate = customValidations('update_unit');
            if(!validate){
                return false;
            }
            var unit_id = $('#update_unit').find('input[name=unit_id]').val();
            return reloadAjaxSubmit('update_unit',"{{ url('unit') }}/"+unit_id,'name','Update');
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
