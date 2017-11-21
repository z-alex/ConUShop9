@extends('layouts.default')
@section('content')

@if($sale)
Thank you for your purchase!

You will see a withdrawal of ${{$sale->get()->payment->get()->amount}} from your bank account.
@endif

@stop
