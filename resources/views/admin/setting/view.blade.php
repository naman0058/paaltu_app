<div class="card">
    <div class="card-header">
        <center> <h6 class="card-title mb-0" style="text-align:center">Settings Details</h6></center>
    </div>
    <div class="card-body">
        <div class="row">
            <label  class="col-md-6"style="float:left">Label</label>
            <h6 class="col-md-6"> {{$settings->label}} </h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Value</label>
            <h6 class="col-md-6"> {{$settings->value}} </h6><br>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{url('setting')}}" class="btn btn-primary" style="float:right">Back</a>
    </div>
</div>