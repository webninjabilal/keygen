@extends('emails.template')

@section('content')

    {{ $customer_name }} is now low on credits for the {{ $machine_name }}.
    Please contact Erchonia to get more credits added for this machine type.
@endsection