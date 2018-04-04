@if($errors->any())
<div class="alert alert-danger">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&cross;</button>
    @foreach($errors->all() as $error)
        {!! $error !!}<br>
    @endforeach
</div>
@endif