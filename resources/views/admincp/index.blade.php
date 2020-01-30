
<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ asset('') }}">

    <title>HRM - SPA</title>
    <!-- Bootstrap -->
    <link href="{{ asset('admin/all.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
  </head>

  <body class="nav-md" >
    <div class="container body" id="app" >
      <div class="main_container">
        <menu-left></menu-left>
        <!-- top navigation -->
        <headertop></headertop>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" >
            {{-- <vue-page-transition name="fade">
              <router-view></router-view>
            </vue-page-transition> --}}
            <router-view></router-view>
            
          <vue-progress-bar></vue-progress-bar>
          <notifications group="infomation" />
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footermain></footermain>
        <!-- /footer content -->
      </div>
        
    
    </div>

    <script src="{{ asset('js/admincp.js') }}"></script>
    <script src="{{ asset('admin/all.js') }}"></script>
  </body>
</html>
