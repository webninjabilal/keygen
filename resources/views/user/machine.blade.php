<div class="col-sm-12">
    <a class="btn btn-primary  pull-right" href="javascript:void(0);" data-toggle="modal" data-target="#create_machine"><i class="fa fa-plus"></i> Add Machine</a>
</div>
<div class="clearfix"></div>
<div class="table-responsive">
    <table id="" class="table table-striped table-bordered table-hover" >
        <thead>
        <tr>
            <th>Nick Name</th>
            <th>Prefix</th>
            <th>Serial</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @if(count($machines) > 0)
                @foreach($machines AS $machine)
                    <tr class="machine_row" data-id="{{ $machine->id }}">
                        <td>{{ $machine->nick_name }}</td>
                        <td>{{ $machine->prefix }}</td>
                        <td>{{ $machine->serial_number }}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-white btn-sm edit_machine"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="javascript:void(0)" class="btn btn-white btn-sm delete_machine"><i class="fa fa-trash-o"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="col-sm-12 pull-right">
        {!! $machines->render() !!}
    </div>
</div>