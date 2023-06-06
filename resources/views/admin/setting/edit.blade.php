@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"><a class="text-secondary" href="{{url('setting')}}">Setting</a></li>
				<li class="breadcrumb-item active" aria-current="page">Edit</li>
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
						<h6 class="card-title mb-0 text-white">Edit Form</h6>
						<div class="dropdown morphing scale-left">
							<a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="" data-bs-original-title="Card Full-Screen" aria-label="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
						</div>
					</div>
					<div class="card-body">
						<form action="{{route('setting.update',$settings->id)}}" method="post">
							@csrf
							@method('PUT')
							<div class="row">
								<div class="form-group col-md-6 mb-3">
									<label>Label </label>
									<input type="text" class="form-control"  value="{{$settings->label}}" name="label" id="label" placeholder="Label" required>
								</div>
								<div class="form-group col-md-6 mb-3">
									<label>Value</label>
									<input type="text" class="form-control" value="{{$settings->value}}" name="value" id="value" placeholder="Value" required>
								</div>
							</div>
							<div class="col-md-12 mb-3" align="right">
								<div class="col-12">
									<button  type="submit" class="btn btn rounded-4 btn-primary edit-setting">Submit</button>
									<a class="btn btn rounded-4 btn-secondary" href="{{ url('setting') }}">Back</a>
								</div>
						    </div>	
						</form>
		        	</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
