<div class="form-group">
    {!! Form::label('machine_id ','Select Machine *') !!}
    {!! Form::select('machine_id' , $machine_list,  null, ['id' => 'sheet_id','class' => 'form-control','placeholder' => 'Select Sheet']) !!}
</div>
<div class="form-group">
    {!! Form::label('serial_number','Serial Number *') !!}
    {!! Form::number('serial_number' , null , ['id' => 'serial_number','class' => 'form-control','placeholder' => '99999', 'maxlength' => '5','required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('uses','Uses *') !!}
    {!! Form::number('uses' , null , ['id' => 'uses','class' => 'form-control','placeholder' => '10', 'maxlength' => '3','required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('used_date','Used Date *') !!}
    {!! Form::text('used_date' , date('m/d/Y'), ['id' => 'used_date','class' => 'form-control date','placeholder' => date('m/d/Y'),'readonly' => 'readonly','required'=>'true']) !!}
</div>