
<div class="form-group">
    {!! Form::label('nick_name','Nick Name *') !!}
    {!! Form::text('nick_name' , $machine->nick_name , ['class' => 'form-control','placeholder' => 'Nick Name','required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('prefix','Prefix *') !!}
    {!! Form::text('prefix' , $machine->prefix , ['class' => 'form-control','placeholder' => 'Prefix','required'=>'true', 'style' => 'width: 170px;', 'maxlength' => '3']) !!}
</div>

<div class="form-group">
    {!! Form::label('serial_number','Serial Number *') !!}
    {!! Form::text('serial_number' , $machine->serial_number , ['class' => 'form-control','placeholder' => '1', 'maxlength' => '5','required'=>'true']) !!}
</div>
<div class="form-group">
    <label>
        {!! Form::checkbox('is_time_base', 1, $machine->is_time_base) !!}
        <strong>Time Based</strong>
    </label>
</div>
<div class="form-group">
    {!! Form::label('sheet_id ','Select Sheet *') !!}
    {!! Form::select('sheet_id' , $sheets, $machine->sheet_id , ['class' => 'form-control','placeholder' => 'Select Sheet']) !!}
</div>

@if($machine->id > 0)
    <input type="hidden" name="machine_id" value="{{ $machine->id }}">
@endif