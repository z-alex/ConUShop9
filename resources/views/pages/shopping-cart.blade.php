@extends('layouts.default')
@section('content')
    <div class="container">
        @if (!empty($slis))
            <h3>Here are the items in your Cart</h3>
            <br>
            <hr>
            @foreach ($slis as $sli)
                @if ( $sli->getElectronicSpecification()->get()->image && $sli->getElectronicSpecification()->get()->image !== null )
                    <img src="{{$sli->getElectronicSpecification()->get()->image}}" class="img-responsive" width="10%" height="auto">
                    <br/>
                @endif
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
                    Price: ${{$sli->getElectronicSpecification()->get()->price}}
                    <br/>
                @endif
                
                Quantity: {{ count($sli->getElectronicItems()) }}
                <a href="/remove-from-cart?eSId={{$sli->getElectronicSpecification()->get()->id}}" class="btn btn-info" role="button"> Remove </a>
                <hr>
            @endforeach
        @else
            <h3>You have no items in your cart</h3>
        @endif
        <a href="/" class="btn btn-info" role="button"> Continue Shopping </a>
    </div>
@stop
