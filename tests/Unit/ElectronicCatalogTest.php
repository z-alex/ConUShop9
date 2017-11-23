<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\Core\ElectronicCatalog;
use App\Classes\Core\ElectronicItem;
use App\Classes\Core\ElectronicSpecification;

class ElectronicCatalogTest extends TestCase {

    public function testModifyElectronicSpecification() {

        $electronicSpecification = new ElectronicSpecification();

        //We create 2 electronic item
        $item1Data = new \stdClass();
        $item1Data->id = 1;
        $item1Data->serialNumber = 123;
        $item1Data->ElectronicSpecification_id = "123";
        $item1Data->User_id = 123;
        $item1Data->expiryForUser = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $item2Data = new \stdClass();
        $item2Data->id = 2;
        $item2Data->serialNumber = 456;
        $item2Data->ElectronicSpecification_id = "123";
        $item2Data->User_id = 123;
        $item2Data->expiryForUser = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        //We plan to add them to the ElectronicData
        $electronicItems = array(new ElectronicItem($item1Data), new ElectronicItem($item2Data));

        $electronicData = new \stdClass();
        $electronicData->id = '123'; //electronic specification id
        $electronicData->dimension = '2X3X4';
        $electronicData->weight = '100';
        $electronicData->modelNumber = '123model';
        $electronicData->brandName = '123';
        $electronicData->hdSize = '123';
        $electronicData->price = '123';
        $electronicData->processorType = 'intel';
        $electronicData->ramSize = '16';
        $electronicData->cpuCores = '4';
        $electronicData->batteryInfo = 'infinite';
        $electronicData->camera = true;
        $electronicData->touchScreen = true;
        $electronicData->displaySize = '1';
        $electronicData->ElectronicType_id = '1';
        $electronicData->image = 'C:/Users/Mel';
        $electronicData->electronicItems = $electronicItems;

        $electronicCatalog = new ElectronicCatalog();
        $electronicCatalog->setESList(array($electronicData));

        //update the os by overwriting previous os value
        $electronicData->dimension = '2X3X5';

        $electronicCatalog->modifyElectronicSpecification(new ElectronicSpecification($electronicData));

        //We get the ElectronicSpecification list from the catalog
        $catalogList = $electronicCatalog->getEsList();

        $catalogListJson = json_decode(json_encode($catalogList), true);
        $electronicDataJson = json_decode(json_encode($electronicData), true);

        //flag for value comparison. if items don't match, $valuesMatch becomes false
        $valuesMatch = false;

        //compare values added from electronicData with the actual
        //ElectronicSpecification object


        foreach ($catalogList as $electronicCatalogItem) {
            $electronicCatalogItemAttributes = get_object_vars($electronicCatalogItem);

            $baseElectronicCatalogAttributesReference = get_object_vars($electronicData);

            //Foreach ElectronicCatalog item
            foreach ($electronicCatalogItemAttributes as $key => $value) {
                //If the attributes are set in both the object and the ElectronicCatalog item
                if (isset($baseElectronicCatalogAttributesReference[$key]) && isset($electronicCatalogItemAttributes[$key]) &&
                        !is_array($electronicCatalogItemAttributes[$key]) && !is_array($baseElectronicCatalogAttributesReference[$key])) {

                    if ($baseElectronicCatalogAttributesReference[$key] == $electronicCatalogItemAttributes[$key]) {
                        $valuesMatch = true;
                    } else {
                        $valuesMatch = false;
                        $this->assertTrue($valuesMatch);
                        break;
                    }
                }
            }
        }

        $this->assertTrue($valuesMatch);
    }

    /**
     * Test the insertion of an electronic item inside an electronic specification in the ElectronicCatalog
     */
    public function testMakeElectronicItem() {
        $item1Data = new \stdClass();
        $item1Data->id = 1;
        $item1Data->serialNumber = 123;
        $item1Data->ElectronicSpecification_id = "1";
        //$electronicItems=array($item1Data);

        $electronicData = new \stdClass();
        $electronicData->id = '1'; //electronic specification id
        $electronicData->dimension = '2X3X4';
        $electronicData->weight = '100';
        $electronicData->modelNumber = '123model';
        $electronicData->brandName = '123';
        $electronicData->hdSize = '123';
        $electronicData->price = '123';
        $electronicData->processorType = 'intel';
        $electronicData->ramSize = '16';
        $electronicData->cpuCores = '4';
        $electronicData->batteryInfo = 'infinite';
        $electronicData->os = 'ubuntu';
        $electronicData->camera = true;
        $electronicData->touchScreen = true;
        $electronicData->displaySize = '1';
        $electronicData->ElectronicType_id = '1';
        $electronicData->electronicItems = array();

        $electronicCatalog = new ElectronicCatalog();

        $electronicCatalog->insertElectronicSpecification(new ElectronicSpecification($electronicData));
        $electronicCatalog->insertElectronicItem(new ElectronicItem($item1Data));

        $catalogList = $electronicCatalog->getEsList();
        $catalogListJson = json_decode(json_encode($catalogList), true);

        $itemDataJson = json_decode(json_encode($item1Data), true);

        $valuesMatch = false;

        $valueOfItemIndex;

        //retrieve which array index is the item we have just added into the
        //specification
        foreach ($catalogListJson[0]["electronicItems"] as $itemIndex => $item) {
            foreach ($item as $attributeKey => $attributeValue) {
                if ($catalogListJson[0]["electronicItems"][$itemIndex]["id"] == $item1Data->id) {
                    $valueOfItemIndex = $itemIndex;
                }
            }
        }
        //compare values of object retrieved with the ones we used to add into
        //the specification
        foreach ($catalogListJson[0]["electronicItems"] as $itemIndex => $item) {

            foreach ($item as $attributeKey => $attributeValue) {

                if ($catalogListJson[0]["electronicItems"][$valueOfItemIndex]["id"] == $item1Data->id) {
                    //We only access the hashtable with a valid entry
                    if (!empty($itemDataJson[$attributeKey])) {
                        if ($attributeValue == $itemDataJson[$attributeKey]) {
                            $valuesMatch = true;
                        } else {
                            $valuesMatch = false;
                            $this->assertTrue($valuesMatch);
                            break;
                        }
                    }
                }
            }
        }
        $this->assertTrue($valuesMatch);
    }

    /**
     * Test the insertion of an ElectronicSpecification in the ElectronicCatalog
     * @return type
     */
    public function testMakeElectronicSpecification() {
        $electronicSpecification = new ElectronicSpecification();

        $item1Data = new \stdClass();
        $item1Data->id = 1;
        $item1Data->serialNumber = 123;
        $item1Data->ElectronicSpecification_id = "123";

        $item2Data = new \stdClass();
        $item2Data->id = 2;
        $item2Data->serialNumber = 456;
        $item2Data->ElectronicSpecification_id = "456";
        $electronicItems = array($item1Data, $item2Data);

        $electronicData = new \stdClass();
        $electronicData->id = '1'; //electronic specification id
        $electronicData->dimension = '2X3X4';
        $electronicData->weight = '100';
        $electronicData->modelNumber = '123model';
        $electronicData->brandName = '123';
        $electronicData->hdSize = '123';
        $electronicData->price = '123';
        $electronicData->processorType = 'intel';
        $electronicData->ramSize = '16';
        $electronicData->cpuCores = '4';
        $electronicData->batteryInfo = 'infinite';
        $electronicData->os = 'ubuntu';
        $electronicData->camera = true;
        $electronicData->touchScreen = true;
        $electronicData->displaySize = '1';
        $electronicData->ElectronicType_id = '1';
        $electronicData->electronicItems = $electronicItems;

        $electronicCatalog = new ElectronicCatalog();

        $electronicCatalog->insertElectronicSpecification(new ElectronicSpecification($electronicData));

        $catalogList = $electronicCatalog->getEsList();

        $catalogListJson = json_decode(json_encode($catalogList), true);
        $electronicDataJson = json_decode(json_encode($electronicData), true);

        //flag for value comparison. if items don't match, $valuesMatch becomes false
        $valuesMatch = false;
        //compare values added from electronicData with the actual
        //ElectronicSpecification object
        foreach ($catalogListJson as $outerKey => $outerValue) {
            foreach ($outerValue as $innerKey => $innerValue) {
                if (is_array($catalogListJson[$outerKey][$innerKey]) == false) {
                    if ($innerKey != "ElectronicType_displaySizeUnit" && $innerKey != "ElectronicType_dimensionUnit" && $innerKey != "ElectronicType_name") {
                        if (isset($catalogListJson[$outerKey][$innerKey]) && $catalogListJson[$outerKey][$innerKey] == $electronicDataJson[$innerKey]) {
                            $valuesMatch = true;
                        } else {
                            $valuesMatch = false;
                            break;
                        }
                    }
                }//if is_array is false
            }
        }
        //foreach loop that checks if the ElectronicItems object attributes
        //match electronicData->electronicItems values added at the beginning of
        //the code
        foreach ($catalogListJson[0]["electronicItems"] as $itemIndex => $item) {
            foreach ($item as $attributeKey => $attributeValue) {

                if (!empty($electronicDataJson["electronicItems"][$itemIndex][$attributeKey])) {

                    if ($attributeValue == ($electronicDataJson["electronicItems"][$itemIndex][$attributeKey])) {
                        $valuesMatch = true;
                    } else {
                        $valuesMatch = false;
                        return;
                    }
                }
            }
        }
        $this->assertTrue($valuesMatch);
    }

    /**
     * Test the obtention of an ElectronicSpecification from the ElectronicCatalog
     */
    public function testGetElectronicSpecificationById() {
        $electronicSpecification = new ElectronicSpecification();

        $item1Data = new \stdClass();
        $item1Data->id = 1;
        $item1Data->serialNumber = 123;
        $item1Data->ElectronicSpecification_id = 123;
        $item1Data->User_id = 123;
        $item1Data->expiryForUser = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $item2Data = new \stdClass();
        $item2Data->id = 2;
        $item2Data->serialNumber = 456;
        $item2Data->ElectronicSpecification_id = 123;
        $item2Data->User_id = 123;
        $item2Data->expiryForUser = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        $electronicItems = array($item1Data, $item2Data);

        $electronicData = new \stdClass();
        $electronicData->id = 123; //electronic specification id
        $electronicData->dimension = '2X3X4';
        $electronicData->weight = '100';
        $electronicData->modelNumber = '123model';
        $electronicData->brandName = '123';
        $electronicData->hdSize = '123';
        $electronicData->price = '123';
        $electronicData->processorType = 'intel';
        $electronicData->ramSize = '16';
        $electronicData->cpuCores = '4';
        $electronicData->batteryInfo = 'infinite';
        $electronicData->os = 'ubuntu';
        $electronicData->camera = true;
        $electronicData->touchScreen = true;
        $electronicData->displaySize = '1';
        $electronicData->ElectronicType_id = '1';
        $electronicData->image = 'C:/Users/Mel';
        $electronicData->electronicItems = $electronicItems;

        $electronicCatalog = new ElectronicCatalog();
        $electronicCatalog->setESList(array($electronicData));
        $electronicCatalogbyId = $electronicCatalog->getElectronicSpecificationById(123)->get();
        $catalogListJson = json_decode(json_encode($electronicCatalogbyId), true);

        $electronicDataJson = json_decode(json_encode($electronicData), true);
        $valuesMatch = false;

        
        //compare values added from electronicData with the actual
        //ElectronicSpecification object
        foreach ($catalogListJson as $key => $value) {
            if (is_array($catalogListJson[$key]) == false) {
                if ($key != "ElectronicType_displaySizeUnit" && $key != "ElectronicType_dimensionUnit" && $key != "ElectronicType_name") {
                    if (isset($catalogListJson[$key]) && $catalogListJson[$key] == $electronicDataJson[$key])
                        $valuesMatch = true;
                    else {
                        $valuesMatch = false;
                        //if the values don't match for the first values,
                        //then this is enough to fail the test
                        //don't execute the rest of the code
                        $this->assertTrue($valuesMatch);
                        break;
                    }
                }
            }
        }
        
        
        //foreach loop that checks if the ElectronicItems object attributes
        //match electronicData->electronicItems values added at the beginning of
        //the code
        foreach ($catalogListJson["electronicItems"] as $itemIndex => $item) {
            foreach ($item as $attributeKey => $attributeValue) {
                if ($attributeValue == ($electronicDataJson["electronicItems"][$itemIndex][$attributeKey]))
                    $valuesMatch = true;
                else {
                    $valuesMatch = false;
                    $this->assertTrue($valuesMatch);
                    break;
                }
            }
        }
        $this->assertTrue($valuesMatch);
    }

    /**
     * Test the finding of an ElectronicSpecification in the ElectronicCatalog
     */
    public function testFindElectronicSpecification() {
        $electronicSpecification = new ElectronicSpecification();
        $electronicItem1 = new ElectronicItem();
        $electronicItem2 = new ElectronicItem();

        $item1Data = new \stdClass();
        $item1Data->id = 1;
        $item1Data->serialNumber = 123;
        $item1Data->ElectronicSpecification_id = "123";

        $item2Data = new \stdClass();
        $item2Data->id = 2;
        $item2Data->serialNumber = 456;
        $item2Data->ElectronicSpecification_id = "456";

        $electronicItem1->set($item1Data);
        $electronicItem2->set($item2Data);
        $electronicItems = array($electronicItem1, $electronicItem2);
        $testModelNumber = "123model";

        $electronicData = new \stdClass();
        $electronicData->id = '1';
        $electronicData->dimension = '2X3X4';
        $electronicData->weight = '100';
        $electronicData->modelNumber = $testModelNumber;
        $electronicData->brandName = '123';
        $electronicData->hdSize = '123';
        $electronicData->price = '123';
        $electronicData->processorType = 'intel';
        $electronicData->ramSize = '16';
        $electronicData->cpuCores = '4';
        $electronicData->batteryInfo = 'infinite';
        $electronicData->os = 'ubuntu';
        $electronicData->camera = true;
        $electronicData->touchScreen = true;
        $electronicData->ElectronicType_id = '1';
        $electronicData->displaySize = '1';
        $electronicData->electronicItems = $electronicItems;

        $electronicSpecification->set($electronicData);
        $electronicCatalog = new ElectronicCatalog();
        $electronicCatalog->setESList(array($electronicData));

        $modelFoundBool = $electronicCatalog->findElectronicSpecification($testModelNumber);
        $this->assertTrue($modelFoundBool);
    }

    public function testDeleteElectronicItem() {
        $electronicSpecification = new ElectronicSpecification();
        $electronicItem1 = new ElectronicItem();
        $electronicItem2 = new ElectronicItem();

        $item1Data = new \stdClass();
        $item1Data->id = 1;
        $item1Data->serialNumber = 123;
        $item1Data->ElectronicSpecification_id = 123;

        $item2Data = new \stdClass();
        $item2Data->id = 2;
        $item2Data->serialNumber = 456;
        $item2Data->ElectronicSpecification_id = 456;
        $electronicItem1->set($item1Data);
        $electronicItem2->set($item2Data);
        $electronicItems = array($electronicItem1, $electronicItem2);

        $electronicData = new \stdClass();
        $electronicData->id = '1'; //all id are int
        $electronicData->dimension = '2X3X4';
        $electronicData->weight = '100';
        $electronicData->modelNumber = '123model'; //string
        $electronicData->brandName = '123';
        $electronicData->hdSize = '123';
        $electronicData->price = '123';
        $electronicData->processorType = 'intel';
        $electronicData->ramSize = '16';
        $electronicData->cpuCores = '4';
        $electronicData->batteryInfo = 'infinite';
        $electronicData->os = 'ubuntu';
        $electronicData->camera = true;
        $electronicData->touchScreen = true;
        $electronicData->ElectronicType_id = '1';
        $electronicData->displaySize = '1';
        $electronicData->electronicItems = $electronicItems;

        $electronicSpecification->set($electronicData);

        $electronicCatalog = new ElectronicCatalog();
        $electronicCatalog->setESList(array($electronicData));
        $catalogListBefore = $electronicCatalog->getESList();


        if (!empty($catalogListBefore) && !empty($catalogListBefore[0])) {
            $sizeElectronicCatalogBefore = sizeof($catalogListBefore[0]->electronicItems);
        }

        $electronicCatalog->deleteElectronicItem(new ElectronicItem($electronicData));

        //update the catalog json after the catalog has been modified
        $catalogListAfter = $electronicCatalog->getESList();

        if (!empty($catalogListAfter) && !empty($catalogListAfter[0])) {
            $sizeElectronicCatalogAfter = sizeof($catalogListAfter[0]->electronicItems);
        }

        $this->assertTrue($sizeElectronicCatalogBefore == 1 + $sizeElectronicCatalogAfter);
    }

    public function testSetGet() {

        $electronicSpecification = new ElectronicSpecification();
        $electronicItem1 = new ElectronicItem();
        $electronicItem2 = new ElectronicItem();

        $item1Data = new \stdClass();
        $item1Data->id = "456";
        $item1Data->serialNumber = 123;
        $item1Data->ElectronicSpecification_id = "123abc";

        $item2Data = new \stdClass();
        $item2Data->id = "456";
        $item2Data->serialNumber = 456;
        $item2Data->ElectronicSpecification_id = "456abc";
        $electronicItem1->set($item1Data);
        $electronicItem2->set($item2Data);
        $electronicItems = array($electronicItem1, $electronicItem2);

        $electronicData = new \stdClass();

        $electronicData->id = '1';
        $electronicData->dimension = '2X3X4';
        $electronicData->weight = '100';
        $electronicData->modelNumber = '123model';
        $electronicData->brandName = '123';
        $electronicData->hdSize = '123';
        $electronicData->price = '123';
        $electronicData->processorType = 'intel';
        $electronicData->ramSize = '16';
        $electronicData->cpuCores = '4';
        $electronicData->batteryInfo = 'infinite';
        $electronicData->os = 'ubuntu';
        $electronicData->camera = true;
        $electronicData->touchScreen = true;
        $electronicData->ElectronicType_id = '1';
        $electronicData->displaySize = '1';
        $electronicData->electronicItems = $electronicItems;

        $electronicSpecification->set($electronicData);

        $electronicCatalog = new ElectronicCatalog();
        $electronicCatalog->setESList(array($electronicData));
        $catalogList = $electronicCatalog->getESList();
        $this->assertTrue(sizeof($catalogList) == sizeof(array($electronicData)));
    }

}
