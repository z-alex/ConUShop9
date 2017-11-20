<!DOCTYPE html>
<html>
    <head>
        @include('includes.head')
    </head>

    <body>
        @include('includes.nav')

        @include('includes.feedback')

        <script type="text/javascript" src="{{ URL::asset('js/modify-validation.js') }}"></script>
        <div class="pageContainer container-fluid">

            <div class="container">

                <div class="col-sm-2"></div>

                <form class="form-horizontal col-sm-8  text-center" action="/modify" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <br />
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="quantity">Add Quantity: </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="quantity" name="quantity" value=0>
                        </div>
                    </div>
                    @yield('content')
                </form>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </body>
</html>
