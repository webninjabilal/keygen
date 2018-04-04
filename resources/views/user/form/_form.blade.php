
    <div class="form-group">
        {!! Form::label('first_name','First Name  *') !!}
        {!! Form::text('first_name' , $user->first_name , ['class' => 'form-control','placeholder' => 'First Name','required'=>'true']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('last_name','Last Name *') !!}
        {!! Form::text('last_name' , $user->last_name , ['class' => 'form-control','placeholder' => 'Last Name ','required'=>'true']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('gender','Gender *') !!}
        {!! Form::select('gender' , \App\User::gender_list(), $user->gender , ['class' => 'form-control','required'=>'true']) !!}
    </div>


    <div class="form-group">
        {!! Form::label('phone','Phone  *') !!}
        {!! Form::text('phone' , $user->phone , ['class' => 'form-control','placeholder' => 'Phone ','required'=>'true']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('email ','Email  *') !!}
        {!! Form::text('email' , $user->email , ['class' => 'form-control','placeholder' => 'Email  ','required'=>'true']) !!}
    </div>


    <div class="form-group">
        {!! Form::label('password','Password') !!}
        {!! Form::password('password' ,  ['class' => 'form-control','required' => (($user->id > 0) ? false : true)]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('user_name','User Login name') !!}
        {!! Form::text('user_name' , $user->user_name, ['class' => 'form-control', 'placeholder' => 'Unique User Login name']) !!}
    </div>
    {{--@if(!isset($is_my_account))
        <div class="form-group">
            {!! Form::label('company_id','Company') !!}
            {!! Form::select('company_id', $company_list, ($user->id > 0) ? \App\Company::userCurrentCompany($user->id) : null , ['id' => 'company_id','class' => 'form-control']) !!}
        </div>
    @endif--}}
    @if(!isset($is_my_account))
        <?php
        $role_id = null;
        $roles = \App\Role::get();
        $role_list = [];

        if(count($roles) > 0) {
            foreach ($roles as $role) {
                if(empty($role->display_name)) {
                    $role->update(['display_name' => $role->name]);
                }
                $role_list[$role->id] = $role->display_name;
            }
        }

        if($user->id > 0) {
            $role_id    = \App\User::getUserRoleId($user->id);
        }
        ?>
        @if(count($role_list) > 1 and Auth::user()->isAdmin())
            <div class="form-group">
                {!! Form::label('role_id','Roles') !!}
                {!! Form::select('role_id', $role_list, $role_id , ['id' => 'role_id','class' => 'form-control']) !!}
            </div>
        @else
            {!! Form::hidden('role_id', 2) !!}
        @endif
    @endif

<div class="clearfix"></div>

@if($user->id > 0)
    <input type="hidden" name="user_id" value="{{ $user->id }}">
@endif