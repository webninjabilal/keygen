<div class="form-group">
    {!! Form::label('name','Name  *') !!}
    {!! Form::text('name' , $user_role->name , ['class' => 'form-control','placeholder' => ' Name','required'=>'true']) !!}
</div>
<div class="form-group">
    {!! Form::label('display_name','Display Name') !!}
    {!! Form::text('display_name' , $user_role->display_name , ['class' => 'form-control','placeholder' => 'Display Name']) !!}
</div>
<div class="form-group">
    {!! Form::label('description','Discription') !!}
    {!! Form::textarea('description' , $user_role->description , ['rows' => 4,'class' => 'form-control','placeholder' => 'Discription']) !!}
</div>
@if($user_role->id > 0)
    <input type="hidden" name="user_role_id" value="{{ $user_role->id }}">
@endif
