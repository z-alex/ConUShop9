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

        <?php $count = 1; ?>
        @foreach($sli->getElectronicItems() as $eI)
        <b>Item {{$count}} expiry: </b>{{$eI->get()->expiryForUser}}
        <br/>
        <?php $count++; ?>
        @endforeach
        <hr>
    </div>
    @endforeach
    <a href="/cancel-checkout" class="btn btn-info" role="button"> Cancel Checkout </a>
    @endif
</div>
@stop
