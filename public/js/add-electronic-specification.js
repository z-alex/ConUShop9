$(document).ready(function () {
    loadForm();

    $("#itemSelect").change(function () {
        loadForm();
    });
});
function loadForm() {
    var htmlString = "";
    switch ($("#itemSelect").val()) {
        case 'empty':
            htmlString = ``;
            break;
        case 'desktop':
            htmlString += `<div class="row">
        <div class="items text-center"><span class="blueTitle">DESKTOP COMPUTER</span></div>
    </div>

            <input type="hidden" name="ElectronicType_id" value=1>
            <input type="hidden" name="ElectronicType_name" value="Desktop">

            <div class="form-group">
                <label class="control-label col-sm-2" for="brandName">Brand</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="brandName" placeholder="Enter brand" name="brandName">
                </div>
            </div>
              <div class="form-group">
            <label class="control-label col-sm-2" for="dimension">Dimension(cm) (wxhxd) <br/> </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="dimension" placeholder="Enter dimensions size (width x height x depth) ex: (32x12x5) " name="dimension">
            </div>
        </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="weight">Weight(kg)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="weight" placeholder="Enter weight" name="weight">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="processorType">Processor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="processorType" placeholder="Enter processor type" name="processorType">
                </div>
            </div>
          <div class="form-group">
                <label class="control-label col-sm-2" for="ramSize">RAM</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ramSize" placeholder="Enter RAM size" name="ramSize">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="cpuCores">Number of CPU cores</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="cpuCores" placeholder="Enter the numbers of CPU cores" name="cpuCores">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="hdSize">Hard Drive(GB)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="hdSize" placeholder="Enter hard drive size" name="hdSize">
                </div>
            </div>

            <div class="form-group">
            <label class="control-label col-sm-2" for="modelNumber">Model Number</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="modelNumber" placeholder="Enter model number" name="modelNumber">
            </div>
        </div>
                
            <div class="form-group">
                <label class="control-label col-sm-2" for="os">Operating System</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="os" placeholder="Enter built-in operating system " name="os">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="price">Price</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="price" placeholder="Enter price" name="price">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="image">Upload product image</label>
                <div class="col-sm-10">
                  <input type="file" name="image">
                </div>
                </form>
            </div>

           
<button type="submit"  class="btn btn-success btn-block">Submit</button>
            </br>
`;
            break;

        case 'laptop':
            htmlString += `<div class="row">
        <div class="items text-center"><span class="blueTitle">LAPTOP</span></div>
    </div>

        <input type="hidden" name="ElectronicType_id" value=2>
        <input type="hidden" name="ElectronicType_name" value="Laptop">
                    
        <div class="form-group">
            <label class="control-label col-sm-2" for="dimension">Dimension(cm) (wxhxd) <br/> </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="dimension" placeholder="Enter dimensions size (width x height x depth) ex: (32x12x5) " name="dimension">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="displaySize">Display Size(inch)</label>
            <div class="col-sm-10">
                <input type="text"  class="form-control" id="displaySize" placeholder="Enter display size" name="displaySize">
            </div>
        </div>

         <div class="form-group">
                <label class="control-label col-sm-2" for="cpuCores">Number of CPU cores</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="cpuCores" placeholder="Enter the numbers of CPU cores" name="cpuCores">
                </div>
            </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="processorType">Processor Type</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="processorType" placeholder="Enter processor type" name="processorType">
            </div>
        </div>

        <div class="form-group">
                <label class="control-label col-sm-2" for="hdSize">Hard Drive(GB)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="hdSize" placeholder="Enter hard drive size" name="hdSize">
                </div>
            </div>

         <div class="form-group">
                <label class="control-label col-sm-2" for="batteryInfo">Battery information</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="batteryInfo" placeholder="Enter battery information" name="batteryInfo">
                </div>
            </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="ramSize">Ram Size</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="ramSize" placeholder="Enter RAM size" name="ramSize">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="weight">Weight(kg)</label>
            <div class="col-sm-10">
                <input type="text"  class="form-control" id="weight" placeholder="Enter weight" name="weight">
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="brandName">Brand Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="brandName" placeholder="Enter brand name" name="brandName">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="modelNumber">Model Number</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="modelNumber" placeholder="Enter model number" name="modelNumber">
            </div>
        </div>

          <div class="form-group">
                <label class="control-label col-sm-2" for="os">Operating System</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="os" placeholder="Enter built-in operating system " name="os">
                </div>
            </div>

         <div class="form-group"> 
            <label class="control-label col-sm-2" for="camera">Camera</label>
            <div class="col-sm-10">
               <input type="radio" name="camera" value="0" checked>No &nbsp; &nbsp; &nbsp;
               <input type="radio" name="camera" value="1" >Yes
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="touchScreen">Touch Screen</label>
            <div class="col-sm-10">
               <input type="radio" name="touchScreen" value="0" checked>No &nbsp; &nbsp; &nbsp;
               <input type="radio" name="touchScreen" value="1" >Yes
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="price">Price</label>
            <div class="col-sm-10">
                <input type="text"  class="form-control" id="price" placeholder="Enter price" name="price">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="image">Upload product image</label>
            <div class="col-sm-10">
              <input type="file" name="image">
            </div>
            </form>
        </div>

        <button type="submit" class="btn btn-success btn-block">Submit</button>
        </br>
`;
            break;

        case 'monitor':
            htmlString += `<div class="row">
        <div class="items text-center"><span class="blueTitle">MONITOR</span></div>
    </div>

            <input type="hidden" name="ElectronicType_id" value=3>
            <input type="hidden" name="ElectronicType_name" value="Monitor">

            <div class="form-group">
                <label class="control-label col-sm-2" for="brandName">Brand</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="brandName" placeholder="Enter brand" name="brandName">
                </div>
            </div>
            
        <div class="form-group">
            <label class="control-label col-sm-2" for="displaySize">Display Size(inch)</label>
            <div class="col-sm-10">
                <input type="text"  class="form-control" id="displaySize" placeholder="Enter display size" name="displaySize">
            </div>
        </div>
           
            <div class="form-group">
                <label class="control-label col-sm-2" for="weight">Weight(kg)</label>
                <div class="col-sm-10">
                    <input type="text"  class="form-control" id="weight" placeholder="Enter weight" name="weight">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="modelNumber">Model</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="modelNumber" placeholder="Enter model" name="modelNumber">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="price">Price</label>
                <div class="col-sm-10">
                    <input type="text"  class="form-control" id="price" placeholder="Enter price" name="price">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="image">Upload product image</label>
                <div class="col-sm-10">
                  <input type="file" name="image">
                </div>
                </form>
            </div>

            

            <button type="submit" class="btn btn-success btn-block">Submit</button>
            <br>
`;
            break;

        case 'tablet':
            htmlString += `<div class="row">
        <div class="items text-center"><span class="blueTitle">TABLET</span></div>
    </div>

            <input type="hidden" name="ElectronicType_id" value=4>
            <input type="hidden" name="ElectronicType_name" value="Tablet">

            <div class="form-group">
                <label class="control-label col-sm-2" for="brandName">Brand</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="brandName" placeholder="Enter brand" name="brandName">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="dimension">Dimensions(cm) (wxhxd)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dimension" placeholder="Enter dimensions size (widthxheightxdepth) " name="dimension">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="display">Display Size(inch)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="displaySize" placeholder="Enter display size" name="displaySize">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="weight">Weight(kg)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="weight" placeholder="Enter weight" name="weight">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="processorType">Processor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="processorType" placeholder="Enter processor type" name="processorType">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="ramSize">RAM</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ramSize" placeholder="Enter RAM size" name="ramSize">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="cpuCores">Number of CPU Cores</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="cpuCores" placeholder="Enter number of CPU cores" name="cpuCores">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="hdSize">Hard Drive(GB)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="hdSize" placeholder="Enter hard drive size" name="hdSize">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="batteryInfo">Battery information</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="batteryInfo" placeholder="Enter battery information" name="batteryInfo">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="modelNumber">Model</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="modelNumber" placeholder="Enter model" name="modelNumber">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="os">Operating System</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="os" placeholder="Enter built-in operating system " name="os">
                </div>
            </div>
         <div class="form-group">
            <label class="control-label col-sm-2" for="camera">Camera</label>
            <div class="col-sm-10">
               <input type="radio" name="camera" value="0" checked>No &nbsp; &nbsp; &nbsp;
               <input type="radio" name="camera" value="1">Yes
            </div>
        </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="price">Price</label>
                <div class="col-sm-10">
                    <input type="text"  class="form-control" id="price" placeholder="Enter price" name="price">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="image">Upload product image</label>
                <div class="col-sm-10">
                  <input type="file" name="image">
                </div>
                </form>
            </div>
            
            <button type="submit" class="btn btn-success btn-block">Submit</button>
            <br>
        `;
            break;
    }
    $("#formLoad").html(htmlString);
}
