
    <div class="form-group">
        {!! Form::label('first_name','First Name  *') !!}
        {!! Form::text('first_name' , $user->first_name , ['id' => 'first_name', 'class' => 'form-control','placeholder' => 'First Name','required'=>'true']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('last_name','Last Name *') !!}
        {!! Form::text('last_name' , $user->last_name , ['id' => 'last_name','class' => 'form-control','placeholder' => 'Last Name ','required'=>'true']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('phone','Phone ') !!}
        {!! Form::text('phone' , $user->phone , ['id' => 'phone','class' => 'form-control','placeholder' => 'Phone ']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('email ','Email  *') !!}
        {!! Form::text('email' , $user->email , ['id' => 'email','class' => 'form-control','placeholder' => 'Email  ','required'=>'true']) !!}
    </div>


    <div class="form-group">
        {!! Form::label('password','Password') !!}
        {!! Form::password('password' ,  ['class' => 'form-control','required' => (($user->id > 0) ? false : true)]) !!}
    </div>

    {{--<div class="form-group">
        {!! Form::label('user_name','User Login name') !!}
        {!! Form::text('user_name' , $user->user_name, ['class' => 'form-control', 'placeholder' => 'Unique User Login name']) !!}
    </div>--}}

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
        @if(isset($is_customer) and $is_customer)
            {!! Form::hidden('role_id', 2) !!}
        @elseif(isset($is_user) and $is_user)
            {!! Form::hidden('role_id', 1) !!}
        @endif
        @if(isset($customer) or $user->customer_id > 0)
            <?php $customer_id = ($user->customer_id > 0) ? $user->customer_id : $customer->id ?>
            {!! Form::hidden('customer_id', $customer_id) !!}
        @endif
        {{--@if(count($role_list) > 1 and Auth::user()->isAdmin())--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('role_id','Roles') !!}--}}
                {{--{!! Form::select('role_id', $role_list, $role_id , ['id' => 'role_id','class' => 'form-control']) !!}--}}
            {{--</div>--}}
        {{--@else--}}
            {{--{!! Form::hidden('role_id', 2) !!}--}}
        {{--@endif--}}
    @endif
<div class="clearfix"></div>

@if($user->id > 0)
    <input type="hidden" name="user_id" value="{{ $user->id }}">
@endif