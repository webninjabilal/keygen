
<div class="form-group">
    {!! Form::label('name','Name *') !!}
    {!! Form::text('name' , $unit->name , ['class' => 'form-control','placeholder' => 'Name','required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('sku','SKU *') !!}
    {!! Form::text('sku' , $unit->sku , ['class' => 'form-control','placeholder' => 'SKU','required'=>'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('description ','Description') !!}
    {!! Form::textarea('description' , $unit->description , ['class' => 'form-control','placeholder' => 'Description']) !!}
</div>

@if($unit->id > 0)
    <input type="hidden" name="unit_id" value="{{ $unit->id }}">
@endif