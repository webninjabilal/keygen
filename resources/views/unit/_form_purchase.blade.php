
{!! Form::open(['onsubmit' => 'return unitToCart();']) !!}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">{{ $unit->name }} - {{ $unit->sku }}</h4>
</div>
<div class="modal-body">
    <div class="form-group">
        {!! Form::label('machine_date','Machine Date *') !!}
        {!! Form::text('machine_date' , date('m/d/Y'), ['id' => 'machine_date','class' => 'form-control date','placeholder' => date('m/d/Y'),'readonly' => 'readonly','required'=>'true']) !!}
        <small>Date as shown on unit</small>
    </div>
    <div class="form-group">
        {!! Form::label('filter_type','Filter *') !!}
        {!! Form::select('filter_type' ,\App\Unit::filter_types(), null, ['id' => 'filter_type','class' => 'form-control unit_filter', 'required'=>'true']) !!}
    </div>
    <?php
        $serial_list = [];
    $serials =  \App\Machine::all_serial(Auth::user());
    if(count($serials) > 0){
        foreach ($serials as $serial) {
            $serial_list[$serial->id] = $serial->nick_name.'('.$serial->prefix.'-'.$serial->serial_number.')';
        }
    }
    ?>
    <div class="form-group">
        {!! Form::label('machine_id','Serial Number: *') !!}
        {!! Form::select('machine_id' ,$serial_list, null, ['id' => 'machine_id','class' => 'form-control user_serials', 'required'=>'true']) !!}
        <small>Serial number of your device</small>
    </div>

    <div class="form-group">
        {!! Form::label('quantity ','Quantity') !!}
        {!! Form::number('quantity' , 1 , ['id' => 'quantity','class' => 'form-control','placeholder' => '1']) !!}
    </div>

    @if($unit->id > 0)
        <input type="hidden" name="unit_id" value="{{ $unit->id }}">
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Add To Cart</button>
</div>
{!! Form::close() !!}