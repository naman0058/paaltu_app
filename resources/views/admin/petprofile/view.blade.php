<div class="card">
    <div class="card-header">
        <center> <h6 class="card-title mb-0" style="text-align:center">Pet Details</h6></center>
    </div>
    <div class="card-body">
		<div class="row">
            <label  class="col-md-6"style="float:left">User Name</label>
            <h6 class="col-md-6">{{ \App\Models\User::getName($profiles->user_id) }}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Pet Name</label>
            <h6 class="col-md-6">{{ ucfirst($profiles->pet_name) }}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Pet Category</label>
            <h6 class="col-md-6">{{ ucfirst($profiles->category->name) }}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Breed</label>
            <h6 class="col-md-6">{{ ucfirst($profiles->breed->breed_name) }}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Gender</label>
            <h6 class="col-md-6">{{ ucfirst($profiles->gender) }}</h6><br>
        </div>
        <div class="row">
            <label  class="col-md-6"style="float:left">Date Of Birth</label>
            <h6 class="col-md-6">{{$profiles->date_of_birth}}</h6><br>
        </div>
        <div class="row">
            <div class="col-md-5">
                <h6 class="card-text">Image</h6>
            </div>
            <div class="col-md-7">
                <p class="card-text">
                    @foreach($petitems as $petitem)
                    <img src="{{asset('images/pet/files')}}/{{$petitem->image}}" width="60px;"height="60px">
                    @endforeach
                </p>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{url('pet-profile')}}" class="btn btn-primary" style="float:right">Back</a>
        </div>
    </div>
</div>