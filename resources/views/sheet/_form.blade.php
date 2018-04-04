
<div class="form-group">
    {!! Form::label('title','Title *') !!}
    {!! Form::text('title' , $sheet->title , ['class' => 'form-control','placeholder' => 'Title','required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('prefix','Prefix *') !!}
    {!! Form::text('prefix' , $sheet->prefix , ['class' => 'form-control','placeholder' => 'Prefix','required'=>'true', 'style' => 'width: 170px;', 'maxlength' => '3']) !!}
</div>

<div class="form-group">
    {!! Form::label('minimum','Minimum *') !!}
    {!! Form::text('minimum' , $sheet->minimum , ['class' => 'form-control','placeholder' => '1', 'maxlength' => '5','required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('maximum','Maximum *') !!}
    {!! Form::text('maximum', $sheet->maximum, ['class' => 'form-control','placeholder' => '99999', 'maxlength' => '5', 'required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('sheet_integers ','Sheet Integer *') !!}
    {!! Form::textarea('sheet_integers' , $sheet->sheet_integers , ['class' => 'form-control','placeholder' => '123,1243,...']) !!}
</div>

@if($sheet->id > 0)
    <input type="hidden" name="sheet_id" value="{{ $sheet->id }}">
@endif