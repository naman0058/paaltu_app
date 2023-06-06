<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/piku.png') }}" /> <!-- Favicon-->

		<title>Dashboard</title>

		@include('layouts.includes.admin.head')
		@section('styles')
		<style>
			 
		</style>
	</head>
	
	<body class="layout-1" data-luno="theme-blue">
		
		@include('layouts.includes.admin.sidebar')
		 
		 	
		<div class="wrapper">
			
			@include('layouts.includes.admin.header')

			@yield('content')

			@include('layouts.includes.admin.footer')
			 

		</div>

		
		<script src="{{ asset('assets/admin/js/theme.js') }}"></script>
		<script src="{{ asset('assets/admin/js/bundle/dataTables.bundle.js') }}"></script>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>	

		<script>
			@if(Session::has('message'))
			
			Swal.fire("{{session()->get('message')}} ");
			@endif
		</script>
		@yield('scripts')
	</body>

</html>