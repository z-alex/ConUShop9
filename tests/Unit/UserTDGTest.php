<?php

/**
 * User : Karine Zhang
 * Date : 2017-10-31
 * @test
 */

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\Core\User;
use App\Classes\Core\UserCatalog;
use App\Classes\TDG\UserTDG;
use App\Classes\Mappers\UserMapper;

class UserTDGTest extends TestCase {

    /**
     * Test the add method in userTDG to see if user got added into the database
     */
    public function testAdd() {
        $userTDG = new UserTDG();

        $userData = new \stdClass();

        //create user with all its parameters 
        $userData->firstName = 'Jay';
        $userData->lastName = 'Lin';
        $userData->email = 'jlin@hotmail.com';
        $userData->phone = '6789998212';
        $userData->admin = 0;
        $userData->physicalAddress = '111 St-Laurent Street';
        $userData->password = '123';
        $user = new User($userData);

        $userTDG->add($user);

        $this->assertDatabaseHas('User', [
            'email' => 'jlin@hotmail.com'
        ]);
    }

    /**
     * Test the login method in userTDG to see if user has been logged in succesfully
     */
    public function testLogin() {
        $userCatalogMapper = new UserMapper();

        $this->assertTrue($userCatalogMapper->login('admin1@conushop.com', 'admin123'));
    }

    /**
     * Test the find method in userTDG, to make sure that a particular user is saved in the database
     */
    public function testFind() {
        $userTDG = new UserTDG();
        $userData = new \stdClass();

        //create user with all its parameters
        $userData->firstName = 'Eric';
        $userData->lastName = 'Watson';
        $userData->email = 'ewat@aol.com';
        $userData->phone = '443-885-3424';
        $userData->physicalAddress = '150 Whatsit Avenue';
        $userData->password = '1e2r3ic';

        $user = new User($userData);
        $userTDG->add($user);

        // Make sure we don't search with information we shouldn't have.
        unset($userData->password);

        // Make sure we only have one result.
        $this->assertTrue(count($userTDG->find($userData)) == 1);
    }

    /**
     * Test the findAll method in userTDG, to make sure that the all users were saved in the database
     */
    public function testFindAll() {
        $userTDG = new UserTDG();

        $userData1 = new \stdClass();
        $userData1->firstName = 'Evan';
        $userData1->lastName = 'Davis';
        $userData1->email = 'eda@gmail.com';
        $userData1->phone = '443-885-3424';
        $userData1->physicalAddress = '150 Whatsit Avenue';
        $userData1->password = 'evanspassword';

        $userData2 = new \stdClass();
        $userData2->firstName = 'Jay';
        $userData2->lastName = 'Lin';
        $userData2->email = 'jlin@hotmail.com';
        $userData2->phone = '6789998212';
        $userData2->physicalAddress = '111 St-Laurent Street';
        $userData2->password = '123';


        $user1 = new User($userData1);
        $user2 = new User($userData2);

        $userTDG->add($user1);
        $userTDG->add($user2);

        // Retrieve the full user list.
        $users = $userTDG->findAll();

        $seenUser1 = false;
        $seenUser2 = false;

        // Check if user1 and user2 are there.
        foreach ($users as $user) {
            $email = $user->email;
            if ($email == $userData1->email) {
                $seenUser1 = true;
            } else if ($email == $userData2->email) {
                $seenUser2 = true;
            }
        }

        // Make sure we saw both users.
        $this->assertTrue($seenUser1 && $seenUser2);
    }

}
