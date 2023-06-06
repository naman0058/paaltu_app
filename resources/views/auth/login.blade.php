@extends('layouts.loginlayout')
@section('content')
<div class="container-fluid">
        <div class="row g-0 justify-content-center">
          <div class="col-xl-4 col-lg-5 col-md-7 col-sm-8" style="background: #fff;">
            <!-- Form -->
            <form class="row g-1 rounded-3 p-lg-5 p-4" method="POST"action="{{ url('login') }}">
                @csrf
              <div class="col-12 text-center mb-5">
               <img src="{{ asset('assets/admin/img/paaltu.jpg') }}" alt="#" class="" style="height: 90px;" />
             
              </div>
             
              <div class="col-12">
                <div class="form-floating">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" name="email" value="{{ old('email') }}">
                  <label>Email address</label>
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
              <div class="col-12">
                <div class="form-floating">
                  <input type="password" class="form-control  @error('password') is-invalid @enderror" placeholder="Password" name="password" id="password">
                  <label>Password</label>
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
              <div class="col-12 d-flex justify-content-between mt-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember"{{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                <a class="text-primary small" href="{{ url('reset_password') }}">Forgot Password?</a>
                @endif
              </div>
              <div class="col-12 text-center mt-4 d-grid">
                <button type="submit" class="btn btn-lg bg-primary-gradient lift text-uppercase" title="">SIGN IN</button>
              </div>
              {{--<div class="col-12 text-center mt-4">
               <span class="text-muted">Don't have an account yet? <a href="{{ url('register') }}">Sign up here</a></span>
              </div>--}}
            </form>
            <!-- End Form -->
          </div>
        </div> <!-- End Row -->
      </div>
@endsection