@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('vendors')}}" aria-current="page">Vendors</a></li>
				<li class="breadcrumb-item active"  aria-current="page">Info</li>
			</ol>
		</div>
	</div>
</div>
<!-- start: page body -->
<div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-3">
	<div class="container-fluid">
		<div class="row g-4">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="card-header"style="background-color:#ad006066;">
						<h6 class="card-title mb-0"style="color:white">Info</h6>
						<div class="dropdown morphing scale-left">
							<a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="" data-bs-original-title="Card Full-Screen" aria-label="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
						</div>
					</div>
					<div class="card-body">
						 
						   	<div class="row">
								<div class="form-group col-md-6 mb-3">
									<label> Name </label> <br>
									<b>{{$data->name}}</b> 
									 
								</div> 
								<div class="form-group col-md-6 mb-3">
									<label> Email </label> <br>
									<b>{{$data->email}}</b> 
									 
								</div>
								<div class="form-group col-md-6 mb-3">
									<label> Mobile </label> <br>
									<b>{{$data->mobile}}</b> 
									 
								</div>
								<div class="form-group col-md-6 mb-3">
									<label>Alternative Mobile </label> <br>
									<b>{{$data->alt_mobile}}</b>
								</div>
								<div class="form-group col-md-6 mb-3">
									<label>Icon</label> <br>
									<img src="{{asset('public/uploads/vendor/'.$data->icon)}}" width="100px">
								</div>
								<div class="form-group col-md-6 mb-3">
									<label>Address</label> <br>
									<p >{{$data->address}}</p>
									 
								</div>
							</div>
							<div class="row mb-3" align="right">
                            	<div class="col-12">
                                
                                <a class="btn btn rounded-4 btn-secondary" href="{{ url('vendors') }}">Back</a>
                            	</div>
                        	</div> 
					</div>	
				</div>
			</div>
		</div> <!-- .row end -->
	</div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$('.dropify').dropify();
</script>
@endsection()
