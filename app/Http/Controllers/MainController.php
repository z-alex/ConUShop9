<?php

namespace App\Http\Controllers;

use Redirect;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Http\Request;
use Session;
use App\Classes\Mappers\UserMapper;
use App\Classes\Mappers\ElectronicCatalogMapper;
use App\Classes\Mappers\SaleMapper;

//reference: https://www.cloudways.com/blog/laravel-login-authentication/
class MainController extends BaseController {

    private $userMapper;
    private $electronicCatalogMapper;
    private $saleMapper;

    public function __construct() {
        $this->userMapper = new UserMapper();
        $this->electronicCatalogMapper = new ElectronicCatalogMapper();

        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->admin == 0) {
                $this->saleMapper = new SaleMapper(auth()->user()->id);
                Session::put('currentSaleExists', $this->saleMapper->currentSaleExists());
            }

            return $next($request);
        });
    }

    public function showLogin() {
        return view('pages.login');
    }

    public function doLogin(Request $request) {
        $inputs = array(
            'email' => $request->input('email'),
            'password' => $request->input('password')
        );

        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:1'
        );

        $validator = Validator::make($inputs, $rules);

        if ($validator->fails()) {
            return Redirect::to('login')->withErrors($validator);
        } else {
            $result = $this->userMapper->login($request->input('email'), $request->input('password')) && Auth::attempt($inputs);
            if ($result) {
                $this->userMapper->makeLoginLog($request->user()->id);
                
                Session::forget('newList');
                Session::forget('changedList');
                Session::forget('deletedList');

                Session::flash('success_msg', "Successfully logged in.");
                return Redirect::to('');
            } else {
                return view('pages.login', ['email' => $request->input('email'), 'error_msg' => 'Unsuccessful login. This account is already logged in or you have entered the wrong email/password.']);
            }
        }
    }

    public function showElectronicCatalog(Request $request) {
        $inputs = $request->all();

        $eSFromType = $this->electronicCatalogMapper->getESByType($request->input('eSType'));
        $electronicSpecifications = $this->electronicCatalogMapper->getESFilteredAndSortedByCriteria($request->input('eSType'), $request->except(['eSType', 'sortBy']), $request->input('sortBy'));

        $brandNames = array();
        foreach ($eSFromType as $eS) {
            if (!in_array($eS->brandName, $brandNames)) {
                array_push($brandNames, $eS->brandName);
            }
        }

        $displaySizes = array();
        foreach ($eSFromType as $eS) {
            if (isset($eS->displaySize) && !in_array($eS->displaySize, $displaySizes) && !is_null($eS->displaySize)) {
                array_push($displaySizes, $eS->displaySize);
            }
        }

        $hasTouchScreen = false;
        if ($request->input('eSType') === 'Laptop') {
            $hasTouchScreen = true;
        }

        $request->session()->put('lastInputs', $inputs);
        $request->session()->put('electronicSpecifications', $electronicSpecifications);

        return view('pages.index', ['electronicSpecifications' => $electronicSpecifications, 'lastInputs' => $inputs, 'brandNames' => $brandNames, 'displaySizes' => $displaySizes, 'hasTouchScreen' => $hasTouchScreen]);
    }

    public function showRegistration() {
        return view('pages.registration');
    }

    public function doRegistration(Request $request) {
        $userData = (object) $request->input();
        $userData->admin = "0";
        if ($this->userMapper->makeNewCustomer($userData)) {
            Session::flash('success_msg', "Successfully registered.");
            return Redirect::to('/');
        } else {
            Session::flash('error_msg', "The Email already exists.");
            return Redirect::back()->withInput();
        }
    }

    public function showDetails(Request $request) {
        $eS = $this->electronicCatalogMapper->getElectronicSpecification($request->input('id'));

        if ($request->input('shoppingCart')) {
            return view('pages.details', ['eS' => $eS, 'shoppingCart' => true]);
        } else if ($request->input('myOrders')) {
            return view('pages.details', ['eS' => $eS, 'myOrders' => true]);
        } else {
            $lastInputs = $request->session()->get('lastInputs');
            $eSpecifications = $request->session()->get('electronicSpecifications');

            foreach ($eSpecifications as $key => $value) {
                if ($eSpecifications[$key]->isDeleted) {
                    unset($eSpecifications[$key]);
                }
            }

            if ($eSpecifications) {
                //Determine previous id of the filtered ES list
                $previousESId = -1;
                $previousES = null;
                foreach ($eSpecifications as $eSpecification) {
                    if ($eSpecification->id === $request->input('id') && $previousES !== null) {
                        $previousESId = $previousES->id;
                        break;
                    }
                    $previousES = $eSpecification;
                }

                //Determine next id of the filtered ES list
                $nextESId = -1;
                $backwards = array_reverse($eSpecifications);
                $nextES = null;
                foreach ($backwards as $eSpecification) {
                    if ($eSpecification->id === $request->input('id') && $nextES !== null) {
                        $nextESId = $nextES->id;
                        break;
                    }
                    $nextES = $eSpecification;
                }

                //Create a query string that will be used to return to the catalog with the same filtering results
                $queryStringBack = "";
                foreach ($lastInputs as $key => $value) {
                    $queryStringBack .= $key . "=" . $value . "&";
                }
                $queryStringBack = rtrim($queryStringBack, '&');

                return view('pages.details', ['eS' => $eS, 'queryStringBack' => $queryStringBack, 'nextESId' => $nextESId, 'previousESId' => $previousESId]);
            } else {
                return view('pages.details', ['eS' => $eS]);
            }
        }
    }

}
