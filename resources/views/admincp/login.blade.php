
<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HRM - Đăng nhập</title>
    <base href="{{asset('')}}" >
    <!-- Bootstrap -->
    <link href="public/admin/all.css" rel="stylesheet">
    <link href="public/admin/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div id="app" >
            <router-view></router-view>
        <notifications group="infomation" />
        
    </div>
    <script src="{{ asset('public/js/admincp.js') }}"></script>
    <script>
        
    </script>
  </body>
</html>
