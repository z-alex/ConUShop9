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
use App\Classes\Mappers\ElectronicCatalogMapper;
use Image;
use Session;

//reference: https://www.cloudways.com/blog/laravel-login-authentication/
class AdminController extends BaseController {

    private $electronicCatalogMapper;

    public function __construct() {
        $this->electronicCatalogMapper = new ElectronicCatalogMapper();

        $this->middleware(function ($request, $next) {

            if (session()->has('newList') || session()->has('changedList') || session()->has('deletedList')) {
                $this->electronicCatalogMapper->setUOWLists(session()->get('newList'), session()->get('changedList'), session()->get('deletedList'));
            }

            return $next($request);
        });
        $this->middleware('auth');
        $this->middleware('CheckAdmin');
    }

    //to locally save the image and also access them publicly,
    //create a simlink that  allow public access to the local images directory with command:
    //php artisan storage:link
    //more info: https://laravel.com/docs/5.5/filesystem#the-public-disk
    public function doAddElectronic(Request $request) {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //image will be saved with timestamp as its name
            $name = time() . '.' . $image->getClientOriginalExtension();
            //file destination  is in 'app/public/image' folder in laravel project
            $destinationPath = public_path('images/' . $name);
            Image::make($image)->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath);

            // direct access to the image with url stored in $url
            $url = asset('/images/' . $name);
        } else {
            $url = null;
        }

        $electronicSpecificationData = (object) $request->except(['_token', 'quantity']);
        $electronicSpecificationData->image = $url;

        if ($this->electronicCatalogMapper->makeNewElectronicSpecificationWithEI($request->input('quantity'), $electronicSpecificationData)) { //
            Session::flash('success_msg', "Successfully added the electronic specification.");
            return Redirect::to('inventory');
        } else {
            Session::flash('error_msg', "The Model number already exists.");
            return Redirect::back()->withInput();
        }
    }

    public function doModifyOrDelete(Request $request) {
        if ($request->input('modifyESButton') !== null) {
            $eSToModify = $this->electronicCatalogMapper->getElectronicSpecification($request->input('modifyESButton'));
            $request->session()->put('eSToModify', $eSToModify);
            switch ($eSToModify->ElectronicType_id) {
                case "1":
                    return view('pages.modify.desktop', ['eSToModify' => $eSToModify]);
                case "2":
                    return view('pages.modify.laptop', ['eSToModify' => $eSToModify]);
                case "3":
                    return view('pages.modify.monitor', ['eSToModify' => $eSToModify]);
                case "4":
                    return view('pages.modify.tablet', ['eSToModify' => $eSToModify]);
            }
            return view('index', ['eSToModify' => $eSToModify]);
        } else if ($request->input('deleteEIButton') !== null) {
            $this->electronicCatalogMapper->prepareDeleteEI($request->input('deleteEIButton'));
            Session::flash('success_msg', "Successfully added to changed item list.");
            return Redirect::back();
        } else if ($request->input('deleteESButton') !== null) {
            $this->electronicCatalogMapper->prepareDeleteES($request->input('deleteESButton'));
            Session::flash('success_msg', "Successfully added to changed item list.");
            return Redirect::back();
        } else if ($request->input('applyChangesButton') !== null) {
            $this->electronicCatalogMapper->applyChanges();
            Session::flash('success_msg', "Successfully applied changes.");
            return Redirect::back();
        } else if ($request->input('cancelChangesButton') !== null) {
            $this->electronicCatalogMapper->cancelChanges();
            Session::flash('success_msg', "Successfully cancelled changes.");
            return Redirect::back();
        } else if ($request->input('addESButton') !== null){
            return Redirect::to('/add-electronic-specification');
        }
    }

    public function doModify(Request $request) {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //image will be saved with timestamp as its name
            $name = time() . '.' . $image->getClientOriginalExtension();
            //file destination  is in 'app/public/image' folder in laravel project
            $destinationPath = public_path('images/' . $name);
            Image::make($image)->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath);

            //Delete old file
            $splitLink = explode("/", $request->session()->get('eSToModify')->image);
            $fileName = end($splitLink);
            if ($fileName !== "" && file_exists(public_path('images/' . $fileName))) {
                unlink(public_path('images/' . $fileName));
            }

            // direct access to the image with url stored in $url
            $url = asset('/images/' . $name);
        } else {
            $url = $request->session()->get('eSToModify')->image;
        }

        $electronicSpecificationData = (object) $request->except(['quantity', '_token']);
        $electronicSpecificationData->image = $url;
        $electronicSpecificationData->id = $request->session()->get('eSToModify')->id;

        if ($this->electronicCatalogMapper->prepareModifyES($request->input('quantity'), $electronicSpecificationData)) {
            Session::flash('success_msg', "Successfully added to changed list.");
            return Redirect::to('inventory');
        } else {
            Session::flash('error_msg', "The model number already exists.");
            return Redirect::back();
        }
    }

    public function showInventory() {
        $electronicSpecifications = $this->electronicCatalogMapper->getAllElectronicSpecifications();

        return view('pages.inventory', ['electronicSpecifications' => $electronicSpecifications]);
    }

    public function showAddElectronic() {
        return view('pages.add-electronic');
    }
    
    public function showAddElectronicSpecification() {
        return view('pages.add-electronic-specification');
    }
    
    public function doAddElectronicSpecification(Request $request) {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //image will be saved with timestamp as its name
            $name = time() . '.' . $image->getClientOriginalExtension();
            //file destination  is in 'app/public/image' folder in laravel project
            $destinationPath = public_path('images/' . $name);
            Image::make($image)->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath);

            // direct access to the image with url stored in $url
            $url = asset('/images/' . $name);
        } else {
            $url = null;
        }

        $electronicSpecificationData = (object) $request->except(['_token', 'quantity']);
        $electronicSpecificationData->image = $url;

        if ($this->electronicCatalogMapper->makeNewElectronicSpecification($electronicSpecificationData)) { //
            Session::flash('success_msg', "Successfully added the electronic specification.");
            return Redirect::to('inventory');
        } else {
            Session::flash('error_msg', "The Model number already exists.");
            return Redirect::back()->withInput();
        }
    }

}
