
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="icon" href="./assets/img/favicon.ico" type="image/x-icon"> <!-- Favicon-->
  <title>:: Sign In :: </title>
  <!-- project css file  -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/luno-style.css') }}">
  <!-- Jquery Core Js -->
  <script src="{{ asset('assets/admin/js/plugins.js') }}"></script>
</head>

<body id="layout-1" data-luno="theme-blue">
  <!-- start: body area -->
  <div class="wrapper">
   
    <!-- Body: Body -->
    <div class="body d-flex p-0 p-xl-5">
      @yield('content')
    </div>
  </div>
  <!-- Jquery Page Js -->
  <script src="{{ asset('assets/admin/js/theme.js') }}"></script>
</body>

</html>
