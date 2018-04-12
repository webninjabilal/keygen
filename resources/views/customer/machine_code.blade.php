<div class="col-sm-2 pull-right" style="text-align: right;">
    <a href="javascript:void(0)" class="btn btn-success export_filter_btn" data-type="codes" style="margin-right: 10px"><i class="fa fa-file-excel-o"></i> Export Codes</a>
</div>
<div class="clearfix"></div>
<div class="table-responsive">
    <table id="" class="table table-striped table-bordered table-hover" >
        <thead>
        <tr>
            <th>Machine Name</th>
            <th>Generated User</th>
            <th>Serial</th>
            <th>Uses</th>
            <th>Machine Date</th>
            <th>Generated Code</th>
            <th>Generated at</th>
        </tr>
        </thead>
        <tbody>
        @if(count($user_machine_codes) > 0)
            @foreach($user_machine_codes AS $user_machine_code)
                <tr class="machine_row" data-id="{{ $user_machine_code->id }}">
                    <td>
                        @if(isset($user_machine_code->machine_user->machine->nick_name))
                            {{ $user_machine_code->machine_user->machine->nick_name }}
                        @elseif(isset($user_machine_code->machine->nick_name))
                            {{ $user_machine_code->machine->nick_name }}
                        @endif
                    </td>
                    <td>{{ (isset($user_machine_code->created_user->full_name)) ? $user_machine_code->created_user->full_name : ''  }}</td>
                    <td>{{ $user_machine_code->serial_number }}</td>
                    <td>{{ $user_machine_code->uses }}</td>
                    <td>{{ (!empty($user_machine_code->used_date)) ? \Carbon\Carbon::createFromFormat('Y-m-d', $user_machine_code->used_date)->format('m/d/Y') : ''  }}</td>
                    <td>{{ $user_machine_code->code }}</td>
                    <td>{{ $user_machine_code->created_at->format('m/d/Y h:i:s A') }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <div class="col-sm-12 pull-right">
        {!! $user_machine_codes->render() !!}
    </div>
</div>