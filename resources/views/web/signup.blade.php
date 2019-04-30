@extends('web.layouts.page')

@section('content')
  <main class="page-container">
    <section class="page-content">
      <h1 class="page-title">Sign Up</h1>

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

      <form class="page-form page-form--signup" method="post" action="{{ url('signup') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="page-form__fields">
          <div class="d-form-group">
            <label for="email">Email Address</label>
            <input type="email" placeholder="Enter email" name="email" id="email" class="d-form-control" required autocomplete="new-password">
          </div>

          <div class="d-form-group">
            <label for="password">Password</label>
            <input type="password" placeholder="Enter password" name="password" id="password" autocomplete="new-password"
                   class="d-form-control" required>
          </div>

          <div class="d-form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" placeholder="Repeat password" name="confirmPassword" id="confirm-password" required
                   class="d-form-control">
          </div>
        </div>

        <div class="page-form__submit-btn">
          <button class="d-btn btn--solid btn--medium" type="submit">Create account</button>
        </div>

        <div class="sign-up-link">
          <a href="/login" class="page-link">Login</a>
        </div>

      </form>
    </section>

    <section class="page-picture page-picture--signup">
      <a href="/jobseeker/signup" class="d-btn btn--blank">I'm a Dental Professional</a>
    </section>

  </main>
@endsection