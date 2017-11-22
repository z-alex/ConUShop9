<?php

namespace App\Http\Controllers;

use Hash;
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
use App\ElectronicTDG;
use Session;
use App\Classes\Mappers\UserMapper;

//reference: https://www.cloudways.com/blog/laravel-login-authentication/
class AuthController extends BaseController {
    private $userCatalogMapper;

    public function __construct() {
        $this->middleware('auth');
        $this->userCatalogMapper = new UserMapper();
    }

    public function doLogout() {
        $this->userCatalogMapper->logout();
        Auth::logout();
        
        Session::flash('success_msg', "Successfully logged out.");
        return redirect('');
    }

}
