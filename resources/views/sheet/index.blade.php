@extends('layouts.app')
@section('extrastyles')
    <link href="{{ asset('/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> Sheets
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_sheet"><i class="fa fa-plus"></i> Add Sheet</a>
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
                        <table id="sheet_list" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Prefix</th>
                                    <th>Minimum</th>
                                    <th>Maximum</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php $sheet = new \App\Sheet(); ?>
    <div class="modal inmodal fade" id="create_sheet" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveSheet();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Sheet</h4>
                </div>
                <div class="modal-body">
                    @include('sheet._form',compact('user'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="update_sheet" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateSheet();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Sheet</h4>
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
            var oTable = $('#sheet_list').dataTable({
                "oLanguage": { "sSearch": "" } ,
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                bSort: false,
                ajax : "{{ route('sheet_records') }}"
            });
        });
        function saveSheet() {
            var validate = customValidations('create_sheet');
            if(!validate){
                return false;
            }
            return reloadAjaxSubmit('create_sheet',"{{ route('sheet.store') }}",'title','Submit');
        }
        function deleteSheet(sheetId) {
            if(confirm('Are you sure you would like to delete it?')) {
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('sheet.destroy', $sheet)}}/'+sheetId,
                    data: 'sheet_id='+sheetId+'&_token='+csrf_token,
                }).done(function() {
                    toastrShow('Sheet deleted Successfully!','Success');
                    location.reload();
                });
            }
        }
        function getSheet(sheetId) {
            $.ajax({
                method: 'GET',
                url: '{{ url('sheet')}}/'+sheetId
            }).done(function(data) {
                $('#update_sheet .modal-body').html(data);
                $('#update_sheet').modal('show');
            });
        }
        function updateSheet() {
            var validate = customValidations('update_sheet');
            if(!validate){
                return false;
            }
            var sheet_id = $('#update_sheet').find('input[name=sheet_id]').val();
            return reloadAjaxSubmit('update_sheet',"{{ url('sheet') }}/"+sheet_id,'title','Update');
        }

        function customValidations(parent_id) {
            var title = $('#'+parent_id+' input[name=title]');
            var prefix = $('#'+parent_id+' input[name=prefix]');
            var minimum = $('#'+parent_id+' input[name=minimum]');
            var maximum = $('#'+parent_id+' input[name=maximum]');
            var sheet_integers = $('#'+parent_id+' textarea[name=sheet_integers]');

            if(title.val() == '') {
                title.focus();
                return false;
            } else if(prefix.val() == '') {
                prefix.focus();
                return false;
            } else if(minimum.val() == '') {
                minimum.focus();
                return false;
            } else if(maximum.val() == '') {
                maximum.focus();
                return false;
            } else if(sheet_integers.val() == '') {
                sheet_integers.focus();
                return false;
            }
            return true;
        }
    </script>
@endsection
