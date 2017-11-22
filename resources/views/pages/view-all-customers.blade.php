@extends('layouts.default')
@section('content')

<form method="post" action="user">

	<div class="col-lg-9">
        <h2 class="blueTitle text-center">Registered Customers</h2>

		<h3> Non-deleted Customers </h3>
    <table>
        <tr class="grayBgWhiteText">
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>PhysicalAddress</th>
        </tr>
	

		@if (!empty($userList))
		@foreach ($userList as $user)
		
		<tr bgcolor="#ccebff">
		<td>
              @if ( isset($user->firstName))
              {{$user->firstName}}  
              @endif  
         </td>
		 <td>
              @if ( isset($user->lastName) )
              {{$user->lastName}}
              @endif  
         </td>
		 <td>
              @if ( isset($user->email) )
              {{$user->email}}
              @endif  
         </td>
		 <td>
              @if ( isset($user->phone) )
              {{$user->phone}}
              @endif  
         </td>
		 <td>
              @if ( isset($user->physicalAddress) )
              {{$user->physicalAddress}}
              @endif  
         </td>

		</tr>
		@endforeach
		@endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </table>
	<br/>
	<br/>

	<h3> Deleted Customers </h3>
	<table>
        <tr class="grayBgWhiteText">
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>PhysicalAddress</th>
        </tr>
	

		@if (!empty($userList))
		@foreach ($userList as $user)
		@if($user->isDeleted==1)
		<tr bgcolor="#ccebff">
		<td>
              @if ( isset($user->firstName))
              {{$user->firstName}}  
              @endif  
         </td>
		 <td>
              @if ( isset($user->lastName) )
              {{$user->lastName}}
              @endif  
         </td>
		 <td>
              @if ( isset($user->email) )
              {{$user->email}}
              @endif  
         </td>
		 <td>
              @if ( isset($user->phone) )
              {{$user->phone}}
              @endif  
         </td>
		 <td>
              @if ( isset($user->physicalAddress) )
              {{$user->physicalAddress}}
              @endif  
         </td>

		</tr>
		@endif
		@endforeach
		@endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </table>
</div>

</form>
@stop