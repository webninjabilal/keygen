<div class="col-sm-12">
    <a class="btn btn-primary  pull-right" href="javascript:void(0);" data-toggle="modal" data-target="#machine_generate_code"><i class="fa fa-plus"></i>  Generate Code</a>
</div>
<div class="clearfix"></div>
<div class="table-responsive">
    <table id="" class="table table-striped table-bordered table-hover" >
        <thead>
        <tr>
            <th>Machine Name</th>
            <th>Serial</th>
            <th>Uses</th>
            <th>Date</th>
            <th>Generated Code</th>
        </tr>
        </thead>
        <tbody>
            @if(count($user_machine_codes) > 0)
                @foreach($user_machine_codes AS $user_machine_code)
                    <tr class="machine_row" data-id="{{ $user_machine_code->id }}">
                        <td>{{ (isset($user_machine_code->machine_user->machine->nick_name)) ? $user_machine_code->machine_user->machine->nick_name : '' }}</td>
                        <td>{{ $user_machine_code->serial_number }}</td>
                        <td>{{ $user_machine_code->uses }}</td>
                        <td>{{ (!empty($user_machine_code->used_date)) ? \Carbon\Carbon::createFromFormat('Y-m-d', $user_machine_code->used_date)->format('m/d/Y') : ''  }}</td>
                        <td>{{ $user_machine_code->code }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="col-sm-12 pull-right">
        {!! $user_machine_codes->render() !!}
    </div>
</div>