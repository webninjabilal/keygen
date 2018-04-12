@extends('layouts.app')
@section('extrastyles')

@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page_heading"> Purchase Units</h2>
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
                            <tbody>
                            @if(count($units) > 0)
                                @foreach($units AS $unit)
                                    <tr class="" data-id="{{ $unit->id }}">
                                        <td>{{ $unit->name }}</td>
                                        <td>{{ $unit->sku }}</td>
                                        <td><a href="javascript:void(0)" class="btn btn-primary add_unit_to_cart"><i class="fa fa-plus"></i> Add Unit</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $unit = new \App\Unit(); ?>
    <div class="modal inmodal fade" id="add_unit" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(document).ready(function () {
            $('.add_unit_to_cart').on('click', function () {
                var unit_id = $(this).closest('tr').data('id');
                if(unit_id > 0) {
                    $.ajax({
                        method: 'get',
                        url: '{{ url('available-unit') }}/'+unit_id,
                        data: 'unit_id='+unit_id+'&_token='+csrf_token,
                    }).done(function(data) {
                        $('#add_unit .modal-content').html(data);
                        dateGroupDiv('#add_unit .date');
                        $('#add_unit').modal('show');
                    });
                }
            });
        });
        function unitToCart() {

            return reloadAjaxSubmit('add_unit',"{{ url('user/add-cart-unit') }}",'title','Submit');
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
