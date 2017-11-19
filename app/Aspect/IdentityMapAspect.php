<?php

namespace App\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;
use Psr\Log\LoggerInterface;
use Session;
use Hash;

/**
 * Application logging aspect (Example provided by goaop-laravel-bridge)
 */
class IdentityMapAspect implements Aspect {

    private $map;

    function __construct() {
        $this->map = array();
    }

    /**
     * Intercept saveES
     *
     * @param MethodInvocation $invocation
     * @After("execution(public App\Classes\Mappers\ElectronicCatalogMapper->saveES(*))")
     */
    public function saveES(MethodInvocation $invocation) {
        /** @var ElectronicCatalogMapper $callee|$this */
        $eS = $invocation->getArguments()[0];

        $this->add('ElectronicSpecification', $eS);
    }

    /**
     * Intercept updateES
     *
     * @param MethodInvocation $invocation
     * @After("execution(public App\Classes\Mappers\ElectronicCatalogMapper->updateES(*))")
     */
    public function updateES(MethodInvocation $invocation) {
        /** @var ElectronicCatalogMapper $callee|$this */
        $eS = $invocation->getArguments()[0];

        $this->modify('ElectronicSpecification', $eS);
    }

    /**
     * Intercept saveEI
     *
     * @param MethodInvocation $invocation
     * @After("execution(public App\Classes\Mappers\ElectronicCatalogMapper->saveEI(*))")
     */
    public function saveEI(MethodInvocation $invocation) {
        /** @var ElectronicCatalogMapper $callee|$this */
        $eI = $invocation->getArguments()[0];

        $this->add('ElectronicItem', $eI);
    }

    /**
     * Intercept deleteEI
     *
     * @param MethodInvocation $invocation
     * @After("execution(public App\Classes\Mappers\ElectronicCatalogMapper->deleteEI(*))")
     */
    public function deleteEI(MethodInvocation $invocation) {
        /** @var ElectronicCatalogMapper $callee|$this */
        $eI = $invocation->getArguments()[0];

        $this->delete('ElectronicItem', $eI);
    }

    /**
     * Intercept deleteES
     *
     * @param MethodInvocation $invocation
     * @After("execution(public App\Classes\Mappers\ElectronicCatalogMapper->deleteES(*))")
     */
    public function deleteES(MethodInvocation $invocation) {
        /** @var ElectronicCatalogMapper $callee|$this */
        $eS = $invocation->getArguments()[0];

        $this->delete('ElectronicSpecification', $eS);
    }
    
    /**
     * Intercept getElectronicSpecification
     *
     * @param MethodInvocation $invocation
     * @Before("execution(public App\Classes\Mappers\ElectronicCatalogMapper->getElectronicSpecification(*))")
     */
    public function getElectronicSpecification(MethodInvocation $invocation) {
        /** @var ElectronicCatalogMapper $callee|$this */
        $id = $invocation->getArguments()[0];

        $eS = $this->get('ElectronicSpecification', 'id', $id);
        
        if($eS !== null){
            return $eS;
        }
    }

    /**
     * Intercept makeNewCustomer
     *
     * @param MethodInvocation $invocation
     * @After("execution(public App\Classes\Mappers\UserCatalogMapper->makeNewCustomer(*))")
     */
    public function makeNewCustomer(MethodInvocation $invocation) {
        $user = $invocation->getThis()->identityUser;

        if (!empty($user)) {
            $this->add('User', $user);
        }
    }

    /**
     * Intercept login
     *
     * @param MethodInvocation $invocation
     * @Before("execution(public App\Classes\Mappers\UserCatalogMapper->login(*))")
     */
    public function login(MethodInvocation $invocation) {
        $email = $invocation->getArguments()[0];
        $password = $invocation->getArguments()[1];

        $user = $this->get('User', 'email', $email);

        if($user) {
            if (Hash::check($user->get()->password, $password)) {
                return true;
            }
        }
    }

    private function add($objectClass, $object) {
        if (Session::has('map')) {
            $this->map = Session::get('map');
        }

        if (isset($this->map[$objectClass])) {
            array_push($this->map[$objectClass], $object);
        } else {
            $this->map[$objectClass] = array();
            array_push($this->map[$objectClass], $object);
        }

        Session::put('map', $this->map);
    }

    private function delete($objectClass, $object) {
        if (Session::has('map')) {
            $this->map = Session::get('map');
        }

        if (isset($this->map[$objectClass])) {
            foreach ($this->map[$objectClass] as $key => $value) {
                if ($this->map[$objectClass][$key]->get()->id === $object->get()->id) {
                    unset($this->map[$objectClass][$key]);
                    break;
                }
            }
        }

        Session::put('map', $this->map);
    }

    private function modify($objectClass, $object) {
        if (Session::has('map')) {
            $this->map = Session::get('map');
        }

        if (isset($this->map[$objectClass])) {
            foreach ($this->map[$objectClass] as $key => $value) {
                if ($this->map[$objectClass][$key]->get()->id === $object->get()->id) {
                    $this->map[$objectClass][$key] = $object;
                    break;
                }
            }
        }

        Session::put('map', $this->map);
    }

    private function get($objectClass, $objectProperty, $objectPropertyValue) {
        if (Session::has('map')) {
            $this->map = Session::get('map');
        }

        if (isset($this->map[$objectClass])) {
            foreach ($this->map[$objectClass] as $object) {
                if ($object->get()->$objectProperty === $objectPropertyValue) {
                    return $object;
                }
            }
        } else {
            $this->map[$objectClass] = array();
        }

        return null;
    }

    private function clear() {
        $this->map = array();
        Session::forget('map');
    }

}
