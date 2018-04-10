
<div class="form-group">
    {!! Form::label('name','Name *') !!}
    {!! Form::text('name' , $customer->name , ['class' => 'form-control','placeholder' => 'Name','required'=>'true']) !!}
</div>
@if(count($machine_list) > 0)
    <?php
    $customer_machines = [];
    if($customer->id > 0)
        $customer_machines = \App\MachineUser::where('customer_id', $customer->id)->active()->pluck('machine_id')->toArray();
    ?>
    <div class="row">
        <div class="col-sm-12">
            <h3>Machines</h3>
            @foreach($machine_list AS $machine)
                <div class="form-group">
                    <label>
                        {!! Form::checkbox('machine_id[]',$machine->id, (in_array($machine->id, $customer_machines) ? true : false)) !!}
                        <strong>{{ $machine->nick_name }}</strong>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if($customer->id > 0)
    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
@endif