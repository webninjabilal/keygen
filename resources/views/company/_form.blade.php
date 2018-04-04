
<div class="form-group">
    {!! Form::label('name','Name *') !!}
    {!! Form::text('name' , $company->name , ['class' => 'form-control','placeholder' => 'Name','required'=>'true']) !!}
</div>


<div class="form-group">
    {!! Form::label('address ','Address') !!}
    {!! Form::textarea('address' , $company->address , ['class' => 'form-control','placeholder' => 'Address']) !!}
</div>

@if($company->id > 0)
    <input type="hidden" name="company_id" value="{{ $company->id }}">
@endif