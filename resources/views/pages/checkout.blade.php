@extends('layouts.default')
@section('content')

<div class="container">
    <h3>Checkout</h3>
    <br>
    <hr>
    @if( !empty($sale->get()->salesLineItemList) )
    @foreach($sale->get()->salesLineItemList as $sli)
   <div class="row">
   <div class="col-sm-2"> 
       <a href="/details?id={{$sli->getElectronicSpecification()->get()->id}}&shoppingCart=true">
           
            @if ( $sli->getElectronicSpecification()->get()->image && $sli->getElectronicSpecification()->get()->image !== null )
                <img src="{{$sli->getElectronicSpecification()->get()->image}}" class="img-responsive" width="100%" height="auto">
       </a>
   </div>
            @endif
            <div class="col-sm-10">
            <a href="/details?id={{$sli->getElectronicSpecification()->get()->id}}&shoppingCart=true">
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
            </a>

        @if ( $sli->getElectronicSpecification()->get()->price )
        <b>Price:</b> ${{$sli->getElectronicSpecification()->get()->price}}
        <br/>
        @endif
        <b>Quantity:</b> {{ count($sli->getElectronicItems()) }}
        <br/>
        <u><b>Subtotal:</b> ${{$sli->getSubtotal()}}</u>
        <br/>
            
   </div>
 </div>   
        <hr>

    @endforeach
            <div class="row">
                <span class="bigText">Total: ${{$sale->getTotal()}}</span>
    <br/>
    <br/>
    <a href="/checkout-pay" class="btn btn-lg btn-success" role="button"> Pay </a>
    <a href="/checkout-cancel" class="btn btn-lg btn-info" role="button"> Cancel Checkout </a>
    @endif
        </div>

@stop
