@extends('layouts.default')
@section('content')
<div class="container">
    @if (!empty($shoppingCart->getSalesLineItems()))
    <h3>Here are the items in your Cart</h3>
    <br>
    <hr>
    @foreach ($shoppingCart->getSalesLineItems() as $sli)
    <div class="row">
   <div class="col-sm-2"> 
       <a href="/details?id={{$sli->getElectronicSpecification()->get()->id}}&shoppingCart=true">
           
        @if ( $sli->getElectronicSpecification()->get()->image && $sli->getElectronicSpecification()->get()->image !== null )
    
        
        <img src="{{$sli->getElectronicSpecification()->get()->image}}" class="img-responsive" width=100%" height="auto">
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
        </a>
        @endif
       

    @if ( $sli->getElectronicSpecification()->get()->price )
    <b>Price:</b> ${{$sli->getElectronicSpecification()->get()->price}}
    <br/>
    
    @endif
    <b>Quantity:</b> {{ count($sli->getElectronicItems()) }}
    <br/>
    <?php $count = 1; ?>
    @foreach($sli->getElectronicItems() as $eI)
    @if($count == 1)
    <b><u>Time that the item(s) will expire from your shopping cart:</u></b>
    <br/>
    @endif
    <b>Item {{$count}} expiry: </b>{{$eI->get()->expiryForUser}}
    <br/>
    <?php $count++; ?>
    @endforeach
    <u><b>Subtotal:</b> ${{$sli->getSubtotal()}}</u>
    <br/>
        
    <a href="/remove-from-cart?eSId={{$sli->getElectronicSpecification()->get()->id}}" class="btn btn-primary" role="button"> Remove </a>
    </div>
    </div>
    <hr>

    @endforeach
    <div class="row">
        <span class="bigText">Total: ${{$shoppingCart->getTotal()}}</span>
 
    <a href="/checkout" class="btn btn-lg btn-info" role="button"> Checkout </a>
    <br/>
        
        
    @else
    <h3>You have no items in your cart</h3>
    @endif
    <br/>
    <a href="/" class="btn btn-primary" role="primary"> Continue Shopping </a>
    </div>
</div>
@stop
