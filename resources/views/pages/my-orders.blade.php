@extends('layouts.default')
@section('content')

<div class="container">
    <h3>My Orders</h3>
    <br>
    <hr>
    @if( isset($orders) && !empty($orders) )
    @foreach($orders as $sale)
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Sale ID:</b> {{$sale->get()->id}}
            <br/>
            <b>Order Placed:</b> {{$sale->get()->timestamp}}
            <br/>
            <b>Transaction Total:</b> ${{$sale->get()->payment->get()->amount}}
            <br/>
        </div>
        <div class="panel-body">
            @if( !empty($sale->get()->salesLineItemList) )
            @foreach($sale->get()->salesLineItemList as $sli)
            <div>
                <a href="/details?id={{$sli->getElectronicSpecification()->get()->id}}&myOrders=true">
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
            @endif
        </div>
    </div>
    @endforeach
    @else
    <h4>You don't have any order.</h4>
    @endif
</div>

@stop
