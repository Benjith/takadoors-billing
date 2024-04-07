@extends('layouts.login_layout')

@section('content')

<div class="row justify-content-center">
				<div class="col-md-7 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
		      	<div class="icon d-flex align-items-center justify-content-center">
		      		<span class="fa fa-user-o"></span>
		      	</div>
		      	<h3 class="text-center mb-4">Sign In</h3>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        @if($errors->any()) 
                            <span class="invalid-feedback d-block" role="alert">
                               {!! implode('', $errors->all('<div>:message</div>')) !!}
                            </span>  
                        @endif
		      		<div class="form-group">
                        <input id="phone" type="text" placeholder="Phone" class="form-control rounded-left @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autofocus>                    
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>         
                        @enderror
                    </div>
                    <div class="form-group d-flex">
                        <input type="password" class="form-control rounded-left" name="password" id="password" placeholder="Password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror    
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
                    </div>
	            <!-- <div class="form-group d-md-flex">
	            	<div class="w-50">
	            		<label class="checkbox-wrap checkbox-primary">Remember Me
									  <input type="checkbox" checked>
									  <span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#">Forgot Password</a>
								</div>
	            </div> -->
	          </form>
	        </div>
				</div>
			</div>
	
            @endsection
			