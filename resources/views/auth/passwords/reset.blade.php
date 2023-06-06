@extends('layouts.loginlayout')
@section('content')
<div class="container-fluid">
        <div class="row g-0 justify-content-center">
          <div class="col-xl-4 col-lg-5 col-md-7 col-sm-8" style="background: #fff;">
             <form class="row g-1 rounded-3 p-lg-5 p-4" method="POST"action="{{ route('password.update') }}">
                @csrf
              <div class="col-12 text-center mb-5">
               <img src="{{ asset('assets/front/assets/images/DMC-Logo.png') }}" alt="#" class="" style="height: 60px;" />
             
              </div>
   

                       {{--<input type="hidden" name="token" value="{{ $token }}">--}}

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

                     

                        {{--<div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>--}}

                         <div class="col-12 text-center mt-4 d-grid">
                <button type="submit" class="btn btn-lg bg-primary-gradient lift text-uppercase" title=""> Reset Password</button>
              </div>
               <span class="text-muted" style="float:left"><a href="{{ url('webadminlogin') }}">Go back</a></span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

