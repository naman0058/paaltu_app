@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('pet-category')}}" aria-current="page">Pet Category</a></li>
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
						<form action="{{route('pet-category.store')}}" method="post"  enctype="multipart/form-data">
							@csrf
						    <div class="form-group col-md-6 mb-3">
								<label> Name </label>
								<input type="text" class="form-control" name="name" id="pet_name_id" placeholder="Name"required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Icon</label>
								<input type="file" class="form-control dropify" name="icon" id="icon_id" placeholder="Icon" required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Description </label>
								<textarea class="form-control" name="description" id="description" placeholder="Description"required></textarea> 
							</div>
							<div class="col-md-12 mb-3" align="right">
                            	<div class="col-12">
                                <button  type="submit"  class="btn btn rounded-4 btn-primary ">Submit</button>
                                <a class="btn btn rounded-4 btn-secondary" href="{{ url('pet-category') }}">Back</a>
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
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$('.dropify').dropify();
</script>
@endsection()
