@extends('web.layouts.page')

@section('content')
  <main class="page-container page--candidate">
    <section class="page-content">
      <h1 class="page-title"></h1>

      @if(Session::has('message'))
            <?php print_r(Session::get('message')) ?>
      @endif

      <p class="page-text">
        Still need the app? Download it on <a href="https://itunes.apple.com/us/app/dentamatch/id1185779291">iOS</a>
        or <a href="https://play.google.com/store/apps/details?id=com.appster.dentamatch">Android</a>.
      </p>

    </section>

    <section class="page-picture page-picture--candidate">
      <a href="/login" class="d-btn btn--blank">I'm a Dental Practice</a>
    </section>

  </main>
@endsection