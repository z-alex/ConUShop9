@extends('layouts.modify')
@section('content')
<div class="row">
    <div class="items text-center"><span class="blueTitle">MODIFY TABLET</span></div>
</div>

<input type="hidden" name="ElectronicType_id" value=4>
<input type="hidden" name="ElectronicType_name" value="Tablet">

<div class="form-group">
    <label class="control-label col-sm-2" for="brandName">Brand</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="brandName" placeholder="Enter brand" name="brandName" value="{{$eSToModify->brandName}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="dimension">Dimensions(cm)(wxhxd)</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="dimension" placeholder="Enter dimensions size (width x height x depth) " name="dimension" value="{{$eSToModify->dimension}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="display">Display Size(inch)</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="displaySize" placeholder="Enter display size" name="displaySize" value="{{$eSToModify->displaySize}}">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="weight">Weight(kg)</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="weight" placeholder="Enter weight" name="weight" value="{{$eSToModify->weight}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="processorType">Processor</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="processorType" placeholder="Enter processor type" name="processorType" value="{{$eSToModify->processorType}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="ramSize">RAM</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="ramSize" placeholder="Enter RAM size" name="ramSize" value="{{$eSToModify->ramSize}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="cpuCores">Number of CPU Cores</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="cpuCores" placeholder="Enter number of CPU cores" name="cpuCores" value="{{$eSToModify->cpuCores}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="hdSize">Hard Drive(GB)</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="hdSize" placeholder="Enter hard drive size" name="hdSize" value="{{$eSToModify->hdSize}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="batteryInfo">Battery</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="batteryInfo" placeholder="Enter battery information" name="batteryInfo" value="{{$eSToModify->batteryInfo}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="modelNumber">Model</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="modelNumber" placeholder="Enter model" name="modelNumber" value="{{$eSToModify->modelNumber}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="os">Operating System</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="os" placeholder="Enter built-in operating system " name="os" value="{{$eSToModify->os}}">
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="price">Camera</label>
    <div class="col-sm-10">
        <input type="radio" name="camera" value="0" checked>No &nbsp; &nbsp; &nbsp;
        <input type="radio" name="camera" value="1">Yes
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="price">Price</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="price" placeholder="Enter price" name="price" value="{{$eSToModify->price}}">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2" for="image">Upload product image</label>
    <div class="col-sm-10">
        <input type="file" name="image">
    </div>
</div>

<button type="submit" class="btn btn-success btn-block">Submit</button>
<br>
@stop