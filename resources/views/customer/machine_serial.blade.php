<?php
$user_generated_codes = $customer_machine->code()->orderBy('created_at', 'desc')->pluck('serial_number', 'id')->toArray();
$user_generated_codes = (array_unique($user_generated_codes));
?>
<div class="table-responsive">
    <table id="" class="table table-striped table-bordered table-hover" >
        <thead>
        <tr>
            <th>Serial</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(count($user_generated_codes) > 0)
            @foreach($user_generated_codes AS $id => $serial_number)
                <tr class="machine_row" data-id="{{ $id }}">
                    <td>{{ $serial_number }}</td>
                    <td>
                        <?php $checkSerial =  $customer_machine->code()->where('id', $id)->first(); ?>
                        @if($checkSerial)
                            @if($checkSerial->block_serial_number == 1)
                                <a href="javascript:void(0)" class="btn btn-primary allow_serial_generate_code">Allow</a>
                            @else
                                <a href="javascript:void(0)" class="btn btn-primary disable_serial_generate_code">Block</a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>