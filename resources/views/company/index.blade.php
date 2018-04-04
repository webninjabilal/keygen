@extends('layouts.app')
@section('extrastyles')

@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> Companies
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_company"><i class="fa fa-plus"></i> Add Company</a>
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
                        <table id="company_list" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php $company = new \App\Company(); ?>
    <div class="modal inmodal fade" id="create_company" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveCompany();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Company</h4>
                </div>
                <div class="modal-body">
                    @include('company._form',compact('user'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="update_company" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateCompany();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Company</h4>
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
            var oTable = $('#company_list').dataTable({
                "oLanguage": { "sSearch": "" } ,
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                bSort: false,
                ajax : "{{ route('company_records') }}"
            });
        });
        function saveCompany() {
            return reloadAjaxSubmit('create_company',"{{ route('company.store') }}",'title','Submit');
        }
        function deleteCompany(companyId) {
            if(confirm('Are you sure you would like to delete it?')) {
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('company.destroy', $company)}}/'+companyId,
                    data: 'company_id='+companyId+'&_token='+csrf_token,
                }).done(function() {
                    toastrShow('Company deleted Successfully!','Success');
                    location.reload();
                });
            }
        }
        function getCompany(companyId) {
            $.ajax({
                method: 'GET',
                url: '{{ url('company')}}/'+companyId
            }).done(function(data) {
                $('#update_company .modal-body').html(data);
                $('#update_company').modal('show');
            });
        }
        function updateCompany() {
            var company_id = $('#update_company').find('input[name=company_id]').val();
            return reloadAjaxSubmit('update_company',"{{ url('company') }}/"+company_id,'name','Update');
        }
    </script>
@endsection
