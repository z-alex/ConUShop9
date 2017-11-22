@extends('layouts.default')
@section('content')
	<form method="post" action="user">

	<div class="col-lg-9">
        <h2 class="blueTitle text-center">My Account</h2>

			<div class="container">
				<div class="row">
					 <div class="col-md-9" id="profile">
						<div class="panel panel-info" style="margin: 1em;">
						  <div class="panel-heading">
							 <h3 class="panel-title">Personal Information</h3>
						  </div>
							<div class="panel-body">
                        
								 First Name : {{$user->get()->firstName}}
								 <br/>
								 Last Name : {{$user->get()->lastName}}
								  <br/>
								 Email : {{$user->get()->email}}
								  <br/>
								 Phone Number : {{$user->get()->phone}}
								  <br/>
								 Address : {{$user->get()->physicalAddress}}
								  <br/>
								  <br/>
								  <div><a href="/delete-my-account" class="btn btn-danger" role="button"> Delete Account </a></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


        <input type="hidden" name="_token" value="{{ csrf_token() }}">
   
	
	
</div>

</form>

@stop