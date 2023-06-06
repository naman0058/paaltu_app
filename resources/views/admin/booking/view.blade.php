<div class="card">
    <div class="card-header">
        <center> <h6 class="card-title mb-0" style="text-align:center">Breed Details</h6></center>
    </div>
    <div class="card-body">
        <div class="row">
            <label  class="col-md-6"style="float:left">Breed Name</label>
            <h6 class="col-md-6">{{ ucfirst($breeds->breed_name) }}</h6><br>
        </div>
         <div class="row">
            <label  class="col-md-6"style="float:left">Pet Category</label>
            <h6 class="col-md-6">{{ ucfirst($breeds->pet_category->name) }}</h6><br>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{url('pet-profile')}}" class="btn btn-primary" style="float:right">Back</a>
    </div>
</div>