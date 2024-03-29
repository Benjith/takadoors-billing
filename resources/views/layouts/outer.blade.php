<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('app.name', 'TAKA') }}</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ URL::asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('vendors/base/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{ URL::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ URL::asset('images/logo.jpg') }}" />



</head>
<body>
  <div class="container-scroller">
    
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href="{{ route('home') }}"><img src="{{ URL::asset('images/logo.jpg') }}" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
       
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile">
            <!-- <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown"> -->
              <span class="nav-profile-name">@if(Auth::user()){{Auth::user()->fullname}}@endif </span>
            <!-- </a> -->
            <!-- <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown"> -->
            <!-- <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
            </a> -->
          </li>
          <li class="nav-item nav-profile">
            <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
            <!-- </div> -->
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/home') }}">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Order</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/closing_stock') }}">
              <i class="mdi mdi-door menu-icon"></i>
              <span class="menu-title">Closing Stock Report</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/agent_wise') }}">
              <i class="mdi mdi-face-agent menu-icon"></i>
              <span class="menu-title">Agent wise Report</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/gate_pass') }}">
              <i class="mdi mdi-routes menu-icon"></i>
              <span class="menu-title">Gate Pass Report</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/orders') }}">
              <i class="mdi mdi-numeric-1-box menu-icon"></i>
              <span class="menu-title">Create Order</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/production') }}">
              <i class="mdi mdi-numeric-2-box menu-icon"></i>
              <span class="menu-title">Production</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/dispatch') }}">
              <i class="mdi mdi-numeric-3-box menu-icon"></i>
              <span class="menu-title">Dispatch</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ URL::to('/driver') }}">
              <i class="mdi mdi-numeric-4-box menu-icon"></i>
              <span class="menu-title">Driver/Route</span>
            </a>
          </li>
        </ul>
      </nav>
      @yield('content')

      <footer class="footer">
       
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{ URL::asset('vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="{{ URL::asset('vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ URL::asset('vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ URL::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ URL::asset('js/off-canvas.js') }}"></script>
  <script src="{{ URL::asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ URL::asset('js/template.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ URL::asset('js/dashboard.js') }}"></script>
  <script src="{{ URL::asset('js/data-table.js') }}"></script>
  <script src="{{ URL::asset('js/jquery.dataTables.js') }}"></script>
  <script src="{{ URL::asset('js/dataTables.bootstrap4.js') }}"></script>
  <!-- End custom js for this page-->

  <script src="{{ URL::asset('js/jquery.cookie.js') }}" type="text/javascript"></script>
</body>

</html>

