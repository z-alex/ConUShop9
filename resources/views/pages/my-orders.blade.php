@extends('layouts.default')
@section('content')
<?php

use App\Classes\Core\ReturnTransaction;
?>
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
            <div class="row">
                <div class="col-sm-2">
                    <a href="/details?id={{$sli->getElectronicSpecification()->get()->id}}&myOrders=true">
                        @if ( $sli->getElectronicSpecification()->get()->image && $sli->getElectronicSpecification()->get()->image !== null )

                        <img src="{{$sli->getElectronicSpecification()->get()->image}}" class="img-responsive" width="100%" height="auto">

                    </a>
                </div>
                @endif
                <div class="col-sm-3">
                    <a href="/details?id={{$sli->getElectronicSpecification()->get()->id}}&myOrders=true">
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
                    Subtotal: ${{$sli->getSubtotal()}}
                    <br/>
                    <a href="/return?eIId={{$sli->getElectronicItems()[0]->get()->id}}" class="btn btn-info" role="button"> Return Item </a>
                    <hr>
                </div>
            </div>
            @endforeach
            Total: ${{$sale->getTotal()}}
            <br/>
            @endif
        </div>
    </div>
    @endforeach

    
    <form method="post" action="/my-orders">
        @if(Session::has('newList') || Session::has('changedList') || Session::has('deletedList'))
        <div class="col-lg-3 panel panel-primary affix" id="changesPanel">
            <div class="panel-heading"> List of Items to Return </div>
            <div class="panel-body">
                @if( !empty(Session::get('newList')) )
                <?php $total = 0; ?>
                @foreach(Session::get('newList') as $new)
                @if($new instanceof ReturnTransaction)
                <b><u>Return Transaction for: </u></b>
                <br/>



                
                @foreach($orders as $order)
                @foreach($order->get()->salesLineItemList as $sli)
                @foreach($sli->getElectronicItems() as $eI)
                @if($eI->get()->id == $new->get()->ElectronicItem_id)

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
                @if ( $sli->getElectronicSpecification()->get()->price )
                <b>Refund Amount:</b> ${{$sli->getElectronicSpecification()->get()->price}}
                <br/>
                @endif
                <?php $total += $sli->getElectronicSpecification()->get()->price; ?>

                @endif
                @endforeach
                @endforeach
                @endforeach
                

                
                

                <br/>
                @endif
                @endforeach
                <b><u>Refund Total:</u></b> ${{$total}}
                
                <br/>
                @endif
                <br/>
                <button type="submit" id="applyReturnsButton" name="applyReturnsButton" class="btn btn-info" value=true>Return</button>
                <button type="submit" id="cancelReturnsButton" name="cancelReturnsButton" class="btn btn-primary" value=true>Cancel Returns</button>
            </div>
        </div>
        @endif

        @else
        <h4>You don't have any order.</h4>
        @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
    
    @if( isset($returns) && !empty($returns) )
    <br/>
    <br/>
    <h3>My Returns</h3>
    @foreach($returns as $return)

    @foreach($eSList as $eS)
    @foreach($eS->electronicItems as $eI)
    @if($eI->id == $return->get()->ElectronicItem_id)

    
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Return Transaction ID:</b> {{$return->get()->id}}
            <br/>
            <b>Return Placed:</b> {{$return->get()->timestamp}}
            <br/>
            <b>Refund:</b> ${{$eS->price}}
            <br/>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2">
                    <a href="/details?id={{$eS->id}}&myOrders=true">
                        @if ( $eS->image && $eS->image !== null )

                        <img src="{{$eS->image}}" class="img-responsive" width="100%" height="auto">

                    </a>
                </div>
                @endif
                <div class="col-sm-3">
                    <a href="/details?id={{$eS->id}}&myOrders=true">
                        @if ( $eS->brandName )
                        {{$eS->brandName}}
                        @endif
                        @if ( $eS->ElectronicType_name )
                        {{$eS->ElectronicType_name}}
                        <br/>
                        @endif
                        @if ( isset($eS->displaySize) )
                        {{$eS->displaySize}} inch display
                        <br/>
                        @endif
                        @if ( $eS->modelNumber )
                        Model {{$eS->modelNumber}}
                        <br/>
                        @endif
                    </a>
                    <br/>
                </div>
            </div>
        </div>
    </div>

    @endif
    @endforeach
    @endforeach

    @endforeach
    @endif
</div>

@stop
