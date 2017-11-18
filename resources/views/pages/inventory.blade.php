@extends('layouts.default')
@section('content')
<script type="text/javascript" src="{{ URL::asset('js/inventory.js') }}"></script>

<form method="post" action="inventory">
    <div class="col-lg-9">
        <h2 class="blueTitle text-center">Inventory</h2>
        
        <button type="submit" id="addESButton" name="addESButton" class="btn btn-primary" value="true">Add Specification</button>
        <br /><br />
        <table>
            <tr bgcolor="#bcbcbc">
                <th>Select</th>
                <th>Select</th>
                <th>ID</th>
                <th>Dimension</th>
                <th>Weight</th>
                <th>Model Number</th>
                <th>Brand Name</th>
                <th>Hard drive size</th>
                <th>Price</th>
                <th>Processor Type</th>
                <th>Ram Size</th>
                <th>CPU Cores</th>
                <th>Battery Info</th>
                <th>OS</th>
                <th>Camera</th>
                <th>Touch Screen</th>
                <th>Display Size</th>
                <th>Electronic Type</th>
                <th>Product Image</th>
            </tr>
            @if (! empty($electronicSpecifications))
            @foreach ($electronicSpecifications as $eS)
            <tr bgcolor="#ededed">
                <td>
                    <button type="submit" id="modifyButton" name="modifyESButton" class="btn btn-xs btn-primary" value="{{$eS->id}}">Modify</button>
                </td>
                <td>
                    <button type="submit" id="deleteESButton" name="deleteESButton" class="btn btn-xs btn-info " value="{{$eS->id}}">Delete</button>
                </td>
                <td>
                    @if ( isset($eS->id) )
                    {{$eS->id}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->dimension) )
                    {{$eS->dimension}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( $eS->weight )
                    {{$eS->weight}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( $eS->modelNumber )
                    {{$eS->modelNumber}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( $eS->brandName )
                    {{$eS->brandName}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->hdSize) )
                    {{$eS->hdSize}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( $eS->price )
                    {{$eS->price}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->processorType) )
                    {{$eS->processorType}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->ramSize) )
                    {{$eS->ramSize}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->cpuCores) )
                    {{$eS->cpuCores}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->batteryInfo) )
                    {{$eS->batteryInfo}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->os) )
                    {{$eS->os}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->camera) && !is_null($eS->camera) )
                    @if ($eS->camera === "1")
                    Yes
                    @else
                    No
                    @endif
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->touchScreen) && !is_null($eS->touchScreen) )
                    @if ($eS->camera === "1")
                    Yes
                    @else
                    No
                    @endif
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( isset($eS->displaySize) && $eS->displaySize )
                    {{$eS->displaySize}} {{$eS->ElectronicType_displaySizeUnit}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( $eS->ElectronicType_name )
                    {{$eS->ElectronicType_name}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    @if ( $eS->image && $eS->image !== null )
                    <img class="imageInv" src="{{$eS->image}}" >
                    @else
                    N/A
                    @endif
                </td>

            </tr>
            @if ($eS->electronicItems)
            @foreach ($eS->electronicItems as $eI)

            <tr>
                <td>

                </td>
                <td>
                    <button type="submit" id="deleteEIButton" name="deleteEIButton" class="btn btn-xs btn-info" value="{{$eI->id}}">Delete</button>
                </td>
                <td>
                    @if ( isset($eI->id) )
                    {{$eI->id}}
                    @else
                    N/A
                    @endif
                </td>
                <td colspan="16">
                    @if ( $eI->serialNumber )
                    <b>Serial Number:</b> {{$eI->serialNumber}}
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
            @endforeach
            @endif
        </table>

    </div>


    <?php

    use App\Classes\Core\ElectronicItem;
    use App\Classes\Core\ElectronicSpecification;
    ?>
    @if(Session::has('newList') || Session::has('changedList') || Session::has('deletedList'))
    <div class="col-lg-3 panel panel-primary affix" id="changesPanel">
        <div class="panel-heading"> Changes </div>
        <div class="panel-body">
            @if( !empty(Session::get('newList')) )
            <h3>New list:</h3>
            @foreach(Session::get('newList') as $new)

            @if($new instanceof ElectronicSpecification)
            <b>Specification </b> 
            @if($new->get()->id)
            <b>ID</b> #{{$new->get()->id}} 
            @endif
            <b>Model: </b> {{$new->get()->modelNumber}}
            @elseif($new instanceof ElectronicItem)
            <b>Item SN:</b> {{$new->get()->serialNumber}} for <b>Specification ID</b> #{{$new->get()->ElectronicSpecification_id}}
            @endif
            <br/>
            @endforeach
            @endif

            @if( !empty(Session::get('changedList')) )
            <h3>Changed list:</h3>
            @foreach(Session::get('changedList') as $changed)
            <b>Specification ID</b> #{{$changed->get()->id}} <b>Model: </b> {{$changed->get()->modelNumber}}
            <br/>
            @endforeach
            @endif

            @if( !empty(Session::get('deletedList')) )
            <h3>Deleted list:</h3>
            @foreach(Session::get('deletedList') as $deleted)
            @if($deleted instanceof ElectronicSpecification)
            <b>Specification ID</b> #{{$deleted->get()->id}} <b>Model: </b> {{$deleted->get()->modelNumber}}
            @elseif($deleted instanceof ElectronicItem)
            <b>Item ID</b> #{{$deleted->get()->id}} <b>SN:</b> {{$deleted->get()->serialNumber}}
            @endif
            <br/>
            @endforeach
            @endif

            <br/>

            <button type="submit" id="applyChangesButton" name="applyChangesButton" class="btn btn-info" value=true>Apply Changes</button>
            <button type="submit" id="cancelChangesButton" name="cancelChangesButton" class="btn btn-primary" value=true>Cancel Changes</button>
        </div>
    </div>
    @endif

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>
@stop
