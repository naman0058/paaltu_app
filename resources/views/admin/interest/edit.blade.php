@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('interest')}}" aria-current="page">Interest</a></li>
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
						<h6 class="card-title mb-0"style="color:white">Edit Form</h6>
						<div class="dropdown morphing scale-left">
							<a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="" data-bs-original-title="Card Full-Screen" aria-label="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
						</div>
					</div>
					<div class="card-body">
						<form action="{{route('interest.update',$interest->id)}}" method="post" id="form">
							@csrf
							@method('PUT')
							<div class="form-group col-md-6 mb-3">
								<label> Date & Time </label>
								<input type="datetime-local" value="{{$interest->date_and_time}}" class="form-control" name="date_and_time" id="date_and_time" placeholder="Date & Time" required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Longitude </label>
								<input type="text" value="{{$interest->longitude}}" class="form-control" name="longitude" id="longitude" placeholder="Longitude" required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Latitude </label>
								<input type="text" value="{{$interest->latitude}}" class="form-control" name="latitude" id="latitude" placeholder="Latitude" required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> From Pet </label>
								<select class="array-select form-control form-select" name="from_pet_id" placeholder="Pet" required>
									<option value="">Select Pet</option>
									@foreach($pets as $pet)
									<option value="{{$pet->id}}" @if($pet->id == $interest->from_pet_id) selected @endif>{{$pet->pet_name}}
									</option>
									@endforeach()
								</select>
							</div>
								<div class="form-group col-md-6 mb-3">
								<label> To Pet </label>
								<select class="array-select form-control form-select" name="to_pet_id" placeholder="Pet" required>
									<option value="">Select Pet</option>
									@foreach($pets as $pet)
									<option value="{{$pet->id}}" @if($pet->id == $interest->to_pet_id) selected @endif>{{$pet->pet_name}}
									</option>
									@endforeach()
								</select>
							</div>
						</div>
						<div class="col-md-12 mb-3" align="right">
							<div class="col-12">
								<button  type="submit" class="btn btn rounded-4 btn-primary ">Submit</button>
								<a class="btn btn rounded-4 btn-secondary" href="{{ url('interest') }}">Back</a>
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