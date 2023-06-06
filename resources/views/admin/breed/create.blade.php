@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('breed')}}" aria-current="page">Breed</a></li>
				<li class="breadcrumb-item active"  aria-current="page">Create</li>
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
						<h6 class="card-title mb-0"style="color:white">Create Form</h6>
						<div class="dropdown morphing scale-left">
							<a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="" data-bs-original-title="Card Full-Screen" aria-label="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
						</div>
					</div>
					<div class="card-body">
						<form action="{{route('breed.store')}}" method="post">
							@csrf
							<div class="form-group col-md-6 mb-3">
								<label>Breed Name </label>
								<input type="text" class="form-control" name="breed_name" id="breed_id" placeholder="Breed Name"required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Pet Category </label>
								<select class="array-select form-control form-select" name="pet_category_id" placeholder="Pet Category" required>
									<option value="">Choose Pet Category</option>
									@foreach($pets as $pet)
									<option value="{{$pet->id}}" {{ old('pet_category_id')== $pet->id ? 'selected': ''}}>{{$pet->name}}
									</option>
									@endforeach()
								</select>
							</div>
							<div class="col-md-12 mb-3" align="right">
								<div class="col-12">
									<button  type="submit"  class="btn btn rounded-4 btn-primary ">Submit</button>
									<a class="btn btn rounded-4 btn-secondary" href="{{ url('breed') }}">Back</a>
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