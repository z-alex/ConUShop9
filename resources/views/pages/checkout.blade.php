@extends('layouts.default')
@section('content')

<div class="container">
    <h3>Checkout</h3>
    <br>
    <hr>
    @if( !empty($sale->get()->salesLineItemList) )
    @foreach($sale->get()->salesLineItemList as $sli)
    <div>
        <a href="/details?id={{$sli->getElectronicSpecification()->get()->id}}&shoppingCart=true">
            @if ( $sli->getElectronicSpecification()->get()->image && $sli->getElectronicSpecification()->get()->image !== null )
            <div>
                <img src="{{$sli->getElectronicSpecification()->get()->image}}" class="img-responsive" width="10%" height="auto">
            </div>
            @endif
            <div>
                @if ( $sli->getElectronicSpecification()->get()->brandName )
                {{$sli->getElectronicSpecification()->get()->brandName}}
                @endif
                @if ( $sli->getElectronicSpecification()->get()->ElectronicType_name )
                {{$sli->getElectronicSpecification()->get()->ElectronicType_name}}
                <br/>
                @endif
                @if ( isset($sli->getElectronicSpecification()->get()->displaySize) )
                {{$sli->getElectronicSpecification()->get()->displaySize}} inch display
                <br/>
                @endif
                @if ( $sli->getElectronicSpecification()->get()->modelNumber )
                Model {{$sli->getElectronicSpecification()->get()->modelNumber}}
                <br/>
                @endif
            </div>
        </a>
        @if ( $sli->getElectronicSpecification()->get()->price )
        <b>Price:</b> ${{$sli->getElectronicSpecification()->get()->price}}
        <br/>
        @endif
        <b>Quantity:</b> {{ count($sli->getElectronicItems()) }}
        <br/>
        Subtotal: ${{$sli->getSubtotal()}}
        <br/>
        <hr>
    </div>
    @endforeach
    Total: ${{$sale->getTotal()}}
    <br/>
    <br/>
    <a href="/checkout-pay" class="btn btn-info" role="button"> Pay </a>
    <a href="/checkout-cancel" class="btn btn-info" role="button"> Cancel Checkout </a>
    @endif
</div>
@stop
