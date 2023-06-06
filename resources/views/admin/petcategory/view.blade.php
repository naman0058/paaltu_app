<div class="card">
    <div class="card-header">
        <center> <h6 class="card-title mb-0" style="text-align:center">Pet Category Details</h6></center>
    </div>
    <div class="card-body">
        <div class="row">
            <label  class="col-md-6"style="float:left">Name</label>
            <h6 class="col-md-6">{{ ucfirst($datas->name) }}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Description</label>
            <h6 class="col-md-6">{!! ucfirst($datas->description)  !!}</h6><br>
        </div>
        <div class="row">
            @if($datas->icon != '')
            <label class="col-md-6" style="float:left">Icon</label>
            <img  src="{{ asset('/public/petcategoryImage') }}/{{ $datas->icon }}" style="width:60px;height:60px;padding:10px">
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{url('pet-category')}}" class="btn btn-primary" style="float:right">Back</a>
    </div>
</div>