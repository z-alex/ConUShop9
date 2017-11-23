<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">ConUShop</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">- Please note this is an university project. Nothing presented on this website is really on sale. - </a></li>
                <li><a href="/">Electronic Catalog</a></li>
                @if( !Auth::check() )
                <li><a href="/login">Log In</a></li>
                <li><a href="/registration">Register</a></li>
                @elseif( Auth::user()->admin === 1 )
                <li><a href="/inventory">Inventory</a></li>
                <li><a href="users">View Users</a></li> 
                <li><a href="/logout">Log Out</a></li>
                @else
                <li><a href="/view-my-account">My Account</a></li>
                <li><a href="/my-orders">My Orders</a></li>
                @if( Session::has('currentSaleExists') && Session::get('currentSaleExists') === true )
                <li><a href="/checkout">Checkout</a></li>
                @else
                <li><a href="/shopping-cart">View Cart</a></li>
                @endif
                <li><a href="/logout">Log Out</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>