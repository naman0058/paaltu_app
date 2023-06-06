<div class="card">
    <div class="card-header">
        <center> <h6 class="card-title mb-0" style="text-align:center">Interest Details</h6></center>
    </div>
    <div class="card-body">
        <div class="row">
            <label  class="col-md-6"style="float:left">Date & Time</label>
            <h6 class="col-md-6">{{$datas->date_and_time}}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Latitude</label>
            <h6 class="col-md-6">{{$datas->latitude}}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Longitude</label>
            <h6 class="col-md-6">{{$datas->longitude}}</h6><br>
        </div>   
        {{--<div class="row">
            <label  class="col-md-6"style="float:left">To  Pet</label>
            <h6 class="col-md-6">{{$datas->to_pet_name}}</h6><br>
        </div>-}}
         {{--<div class="row">
            <label  class="col-md-6"style="float:left">To Pet</label>
            <h6 class="col-md-6">{{$interests->pet_profile->pet_name}}</h6><br>
        </div>--}}
    </div>
    <div class="card-footer">
        <a href="{{url('pet-profile')}}" class="btn btn-primary" style="float:right">Back</a>
    </div>
</div>