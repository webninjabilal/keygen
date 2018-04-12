@extends('layouts.app')
@section('extrastyles')

@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page_heading"> Customers
                    <div class="pull-right">
                        <a class="btn btn-primary" href="javascript:void(0);" data-toggle="modal" data-target="#create_customer"><i class="fa fa-plus"></i> Add Customer</a>
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
                        <table id="customer_list" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Machine Type</th>
                                    <th>Credit Pool</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php $customer = new \App\Customer(); ?>
    <div class="modal inmodal fade" id="create_customer" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return saveCustomer();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Customer</h4>
                </div>
                <div class="modal-body">
                    @include('customer._form',compact('customer'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="update_customer" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['method' => 'PUT' , 'onsubmit' => 'return updateCustomer();']) !!}
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
    <script>
        $(document).ready(function () {
            var oTable = $('#customer_list').dataTable({
                "oLanguage": { "sSearch": "" } ,
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                bSort: false,
                ajax : "{{ route('customer_records') }}"
            });
        });
        function saveCustomer() {
            return reloadAjaxSubmit('create_customer',"{{ route('customer.store') }}",'name','Submit');
        }
        function deleteCustomer(customerId) {
            if(confirm('Are you sure you would like to delete it?')) {
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('customer.destroy', $customer)}}/'+customerId,
                    data: 'customer_id='+customerId+'&_token='+csrf_token,
                }).done(function() {
                    toastrShow('Customer deleted Successfully!','Success');
                    location.reload();
                });
            }
        }
        function getCustomer(customerId) {
            $.ajax({
                method: 'GET',
                url: '{{ url('customer')}}/'+customerId
            }).done(function(data) {
                $('#update_customer .modal-body').html(data);
                $('#update_customer').modal('show');
            });
        }
        function updateCustomer() {
            var customer_id = $('#update_customer').find('input[name=customer_id]').val();
            return reloadAjaxSubmit('update_customer',"{{ url('customer') }}/"+customer_id,'name','Update');
        }
    </script>
@endsection
