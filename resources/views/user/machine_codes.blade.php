@extends('layouts.app')
@section('extrastyles')
    <link href="{{ asset('/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row">
            <div class="col-lg-12">
                <h2> {{ \Auth::user()->full_name }} Codes</h2>
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
                        @include('user.machine')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $machine = new \App\MachineUser(); ?>
    <div class="modal inmodal fade" id="machine_generate_code" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['onsubmit' => 'return generateMachineCode();']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Generate Code</h4>
                </div>
                <div class="modal-body">
                    @include('user.form._machine_generate_code',compact('machine'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('footer')

    <script>
        function generateMachineCode() {
            return reloadAjaxSubmit('machine_generate_code',"{{ route('user_machine_generate_code') }}",'machine_id','Submit');
        }
    </script>
@endsection