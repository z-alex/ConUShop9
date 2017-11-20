<?php

namespace App\Classes\Core;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use Notifiable;

    //Attributes used by Laravel, they are not in the scope of this course
    public $table = "user";
    public $timestamps = false;
    
    //Attributes
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $admin;
    private $physicalAddress;
    private $password;
    public $isDeleted;
    public $isLoggedIn;

    //source for multiple constructors: http://www.webtrafficexchange.com/multiple-constructors-php

    /**
     * User constructor.
     */
            function __construct() {
        $argv = func_get_args();
        switch (func_num_args()) {
            case 1:
                self::__construct1($argv[0]);
                break;
        }
    }

    function __construct1($data) {
        $this->set($data);
    }

    public function set($data) {
        if (isset($data->id)) {
            $this->id = $data->id;
        }
        if (isset($data->firstName)) {
            $this->firstName = $data->firstName;
        }
        if (isset($data->lastName)) {
            $this->lastName = $data->lastName;
        }
        if (isset($data->email)) {
            $this->email = $data->email;
        }
        if (isset($data->phone)) {
            $this->phone = $data->phone;
        }
        if (isset($data->admin)) {
            $this->admin = $data->admin;
        }
        if (isset($data->physicalAddress)) {
            $this->physicalAddress = $data->physicalAddress;
        }
        if (isset($data->password)) {
            $this->password = $data->password;
        }
        if (isset($data->isLoggedIn)) {
            $this->isLoggedIn = $data->isLoggedIn;
        }
        if(isset($data->isDeleted)){
            $this->isDeleted = $data->isDeleted;
        }
    }

    public function get() {
        $returnData = new \stdClass();

        $returnData->id = $this->id;
        $returnData->firstName = $this->firstName;
        $returnData->lastName = $this->lastName;
        $returnData->email = $this->email;
        $returnData->phone = $this->phone;
        $returnData->admin = $this->admin;
        $returnData->physicalAddress = $this->physicalAddress;
        $returnData->password = $this->password;
        $returnData->isLoggedIn = $this->isLoggedIn;
        $returnData->isDeleted = $this->isDeleted;

        return $returnData;
    }

}
