@extends('web.layouts.page')

@section('content')
  <main class="page-container page--login">
    <section class="page-content">
      <h1 class="page-title">Dental Practice <br>
        Login Here</h1>

      <p class="page-text">
        Please sign up or login to access our dental office portal.
      </p>

      @if(Session::has('message'))
        <h6 class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ Session::get('message') }}</h6>
      @endif
      @if(Session::has('success'))
        <h6 class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ Session::get('success') }}</h6>
      @endif

      <form method="post" action="{{ url('login') }}" class="page-form page-form--login">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="page-form__fields">
          <div class="d-form-group">
            <label for="email">Email Address</label>
            <input type="email" placeholder="Enter email" name="email" id="email" class="d-form-control" tabindex="1"
                   data-parsley-required-message="Email required" required>
          </div>

          <div class="d-form-group">
            <label for="password">Password</label>
            <input type="password" placeholder="Enter password" name="password" id="password" tabindex="2"
                   required
                   class="d-form-control">
          </div>
        </div>

        <div class="page-form__submit-btn">
          <button class="d-btn btn--gradient btn--mini" type="submit" tabindex="3">Login</button>
        </div>

        <div class="forgot-link">
          <a href="{{url('password/reset')}}" class="page-link">Forgot password?</a>
        </div>

        <div class="sign-up-link">
          <a href="/signup" class="d-btn btn--outline">Create a Dental Office Account</a>
        </div>

      </form>
    </section>

    <section class="page-picture page-picture--login">
      <a href="/jobseeker/signup" class="d-btn btn--outline btn--large">Job Seekers Sign Up Here</a>
    </section>

  </main>
@endsection