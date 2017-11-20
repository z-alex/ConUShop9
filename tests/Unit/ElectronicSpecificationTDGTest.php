<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\TDG\ElectronicSpecificationTDG;
use App\Classes\Core\ElectronicSpecification;

class ElectronicSpecificationTDGTest extends TestCase {

    public function testInsertAndFindES() {
        $electronicSpecificationTDG = new ElectronicSpecificationTDG();

        $eSData = new \stdClass();
        
        //$eSData->id = 1;
        //$eSData->dimension = '100 x 200 x 300';
        $eSData->weight = 400;
        $eSData->modelNumber = 'E4R2G2GS3D4';
        $eSData->brandName = 'LG';
        //$eSData->hdSize = '500';
        $eSData->price = '1000';
        //$eSData->processorType = 'AMD';
        //$eSData->ramSize = '16';
        //$eSData->cpuCores = '4';
        //$eSData->batteryInfo = '12 hours';
        //$eSData->os = 'Windows';
        //$eSData->camera = 1;
        //$eSData->touchScreen = 1;
        //$eSData->displaySize = 10;
        $eSData->ElectronicType_id = 3;

        $eS = new ElectronicSpecification($eSData);
        
        $eSData = (array) $eSData;
        
        $insertedId = $electronicSpecificationTDG->insert($eS);
        
        $foundES = (array) $electronicSpecificationTDG->find(['id' => $insertedId])[0];
        
        $sameValues = true;
        
        foreach($eSData as $key => $value){            
            if($key!= 'id' && $eSData[$key] != $foundES[$key]){
                $sameValues = false;
                break;
            }
        }
        
        $this->assertTrue($sameValues);
    }

     public function testUpdateES(){
        $electronicSpecificationTDG = new ElectronicSpecificationTDG();
        $newES= new ElectronicSpecification();
         
        $newESData= new \stdClass();
        $newESData->id = 1;
        //$newESData->dimension = '200 x 400 x 500';
        $newESData->weight = 500;
        $newESData->modelNumber = 'AHZ3011HLAZ';
        $newESData->brandName = 'Samsung';
        //$newESData->hdSize = '600';
        $newESData->price = '1200';
        //$newESData->processorType = 'AMD';
        //$newESData->ramSize = '8';
        //$newESData->cpuCores = '3';
        //$newESData->batteryInfo = '6 hours';
        //$newESData->os = 'Windows';
        //$newESData->camera = 1;
        //$newESData->touchScreen = 1;
        //$newESData->displaySize = 20;
        $newESData->ElectronicType_id = 3;
        
        $newES->set($newESData);
        
        $electronicSpecificationTDG->update($newES);
        
        
        //'dimension' => '200 x 400 x 500',
        //'hdSize' => '600',
        //'processorType' => 'AMD',
        //'ramSize' => '8',
        //'cpuCores' => '3',
        //'batteryInfo' => '6 hours',
        //'os' => 'Windows',
        //'camera' => 1,
        //'touchScreen' => 1,
        //'displaySize' => 20,
        
        $this->assertDatabaseHas('ElectronicSpecification', [
            'id' => 1,
            'weight' => 500,
            'modelNumber' => 'AHZ3011HLAZ',
            'brandName' => 'Samsung',
            'price' => '1200',
            'ElectronicType_id' => 3,
        ]);
        
     } 
    
}
