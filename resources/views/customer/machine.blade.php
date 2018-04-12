<div class="table-responsive">
    <table id="" class="table table-striped table-bordered table-hover" >
        <thead>
        <tr>
            <th>Machine Name</th>
            <th>Credit Pool</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="machine_listing">
        @if(count($customer_machines) > 0)
            @foreach($customer_machines AS $customer_machine)
                <tr data-id="{{ $customer_machine->id }}">
                    <td>{{ ($customer_machine->machine->nick_name) ? $customer_machine->machine->nick_name : 'Machine has deleted' }}</td>
                    <td><input type="text" class="machine_credits" name="machine_credits[]" value="{{ $customer_machine->credits }}"></td>
                    <td>
                        @if($customer_machine->allow_generate_code == 1)
                            <a href="javascript:void(0)" class="btn btn-primary disable_generate_code">Block</a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-primary allow_generate_code">Allow</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <div class="col-sm-12 pull-right">
        {!! $user_machine_codes->render() !!}
    </div>
</div>