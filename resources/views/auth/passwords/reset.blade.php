@extends('web.layouts.page')

@section('content')

    <main class="page-container page--forgot">
        <section class="page-content">
            <h1 class="page-title">Reset Password</h1>

            {{--<p class="page-text">--}}
                {{--Enter your email address so we can email you a link to reset your password.--}}
            {{--</p>--}}

            <form class="page-form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" class="form-control" name="email" value="{{ $email }}">

                @if ($errors->has('email'))
                    <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                @endif
                @if ($errors->has('password'))
                    <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                @endif
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                @endif

                <div class="page-form__fields">
                    <div class="d-form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">New Password</label>
                        <input type="password" placeholder="Enter password" name="password" id="password" class="d-form-control" required>
                    </div>
                    <div class="d-form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" placeholder="Confirm password" name="password_confirmation" id="password_confirmation" class="d-form-control" required>
                    </div>
                </div>

                <div class="page-form__submit-btn">
                    <button class="btn btn--solid btn--mini" type="submit">Update</button>
                </div>

            </form>
        </section>

        <section class="page-picture page-picture--remember">
            <a href="/jobseeker/signup" class="btn btn--blank">I'm a Dental Professional</a>
        </section>

    </main>

@endsection