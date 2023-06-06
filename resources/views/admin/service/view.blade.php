<div class="card">
    <div class="card-header">
        <center> <h6 class="card-title mb-0" style="text-align:center">Service Details</h6></center>
    </div>
    <div class="card-body">
        <div class="row">
            <label  class="col-md-6"style="float:left">Service Name</label>
            <h6 class="col-md-6">{{$services->service_name}}</h6><br>
        </div>
        <div class="row">
            @if($services->icon != '')

            <label class="col-md-6" style="float:left">Icon</label>
            <img  src="{{ asset('/public/serviceImage') }}/{{ $services->icon }}" style="width:60px;height:60px;padding:10px">
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{url('service')}}" class="btn btn-primary" style="float:right">Back</a>
    </div>
</div>