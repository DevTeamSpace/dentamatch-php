@extends('web.layouts.page')

@section('content')

  <main class="page-container page--forgot">
    <section class="page-content">
      <h1 class="page-title">Forgot Password</h1>

      <p class="page-text">
        Enter your email address so we can email you a link to reset your password.
      </p>

      <form class="page-form" method="POST" action="{{ url('/password/email') }}">
        {!! csrf_field() !!}

        @if (session('status'))
          <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session('status') }}
          </div>
        @endif
        @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif

        <div class="page-form__fields">
          <div class="d-form-group {{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">Email Address</label>
            <input type="email" placeholder="Enter email" name="email" id="email" class="d-form-control" tabindex="1" required>
          </div>
        </div>

        <div class="page-form__submit-btn">
          <a class="d-btn btn--outline-grey btn--mini" tabindex="3" href="{{url('login')}}">Cancel</a>
          <button class="d-btn btn--solid btn--mini" type="submit" tabindex="2">Send</button>
        </div>

      </form>
    </section>

    <section class="page-picture page-picture--remember">
      <a href="/jobseeker/signup" class="d-btn btn--blank">I'm a Dental Professional</a>
    </section>

  </main>

@endsection