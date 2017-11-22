@extends('layouts.default')
@section('content')
<div class="pageContainer text-center">
@if($sale)
<div class="col-sm-2"></div>
<div class="col-sm-8">
<span class="bigText">Thank you for your purchase!<br/>

You will see a withdrawal of ${{$sale->get()->payment->get()->amount}} from your bank account.</span><br/><br/>
<a href="/my-orders" class="btn btn-lg btn-info" role="button"> View My Orders </a>
@endif
</div>
<div class="col-sm-2"></div>
</div>
@stop
