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
        
        $eSData->weight = 400;
        $eSData->modelNumber = 'E4R2G2GS3D4';
        $eSData->brandName = 'LG';
        $eSData->price = '1000';
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
        $newESData->weight = 500;
        $newESData->modelNumber = 'AHZ3011HLAZ';
        $newESData->brandName = 'Samsung';
        $newESData->price = '1200';
        $newESData->ElectronicType_id = 3;
        
        $newES->set($newESData);
        
        $electronicSpecificationTDG->update($newES);
        
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
