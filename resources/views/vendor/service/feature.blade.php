@extends('layouts.adminlayout')
@section('content')
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('my-service')}}" aria-current="page">Service</a></li>
				<li class="breadcrumb-item active"  aria-current="page"> Feature Create</li>
			</ol>
		</div>
	</div>
</div>
<div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-3">
	<div class="container-fluid">
		<div class="row g-4">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="card-header"style="background-color:#3fbb7066;">
						<h6 class="card-title mb-0"style="color:white">Create Feature Form</h6>
						<div class="dropdown morphing scale-left">
							<a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="" data-bs-original-title="Card Full-Screen" aria-label="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
						</div>
					</div>
					<div class="card-body">
						<form action="{{route('my-featureStore')}}" method="post"  enctype="multipart/form-data">
							@csrf
							<button class="btn btn-primary"  style="float:right" type="button" id="addFeatureBtn">+</button>
							<div class="form-group col-md-6 mb-3">
								<label> Title </label>
								<input type="text" class="form-control" name="title" id="title" placeholder="Title"required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Description </label>
								<textarea class="form-control" name="description" id="description" placeholder="Description"required></textarea>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Image</label>
								<input type="file" class="form-control " name="image" id="image" placeholder="Image" required>
							</div>
							<div id="appendFeature"></div>
						<input type="hidden" id="serviceFeature" name="serviceFeature" value="{{$service_id}}">
						<div class="col-md-12 mb-3" align="right">
								<div class="col-12">
									<button  type="submit"  class="btn btn rounded-4 btn-primary ">Submit</button>
									<a class="btn btn rounded-4 btn-secondary" href="{{ url('service') }}">Back</a>
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
<script>
var c=0;
$(document).on('click','#addFeatureBtn',function(){
c++;
var template='<div class="row" id="row_'+c+'"><div class="col mb-3" style="border:grey; border-width:1px; border-height:1px; border-style:solid;"><div class="col-md-6  mb-3"><label>Title</label><input type="text" name="title" class="form-control"><br><label>Image</label><input type="file" name="image" class="form-control"><br><label>Description</label><textarea class="form-control" name="description"></textarea></div></div><div class="col-md-4  mb-3"><button type="button"class="btn btn-danger removeClass" value="'+c+'">-</button></div></div>';
$('#appendFeature').append(template);
});
$(document).on('click','.removeClass',function(){
var id=$(this).val();
$('#row_'+id).remove();
});
</script> 
@endsection()
