<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
  <link rel="stylesheet" href="{{asset('/css/landing.css')}}">

  <title>Dental Professional Sign Up | DentaMatch</title>
</head>
<body>

<header class="d-container main-header">
  <a href="/">
    <img src="/assets/img/logo/group.png"
         srcset="/assets/img/logo/group@2x.png 2x,
             /assets/img/logo/group@3x.png 3x"
         class="main-logo" width="162" height="30" alt="DentaMatch logo">

    <img src="/assets/img/logo/group-10@3x.png" class="main-logo--big" width="323" height="60" alt="DentaMatch logo">
  </a>

  <a href="/signup" class="main-header__link">Dental practices sign up here </a>
</header>
<main>
  <section class="center-second">
    <div class="d-container">
      <h1 class="visually-hidden">DentaMatch for Dental Professional</h1>
      <b class="lead-text">Find dental jobs near&nbsp;you</b>
      <span class="center-sub-title center-sub-title--extra">Whether you’re looking to pick up a day here and there or find new full time job,
        DentaMatch is the easy way for dental professionals to find work fast.</span>

      <img src="/assets/img/svg/dude-behind-desk.svg" class="center-img">

      <div class="full-width">
        <img src="/assets/img/dude-behind-desk-mob.png" class="center-img--mobile">
      </div>

      <div class="app-links">
        <a class="d-btn btn--outline app-link app-link--apple" href="https://itunes.apple.com/us/app/dentamatch/id1185779291" target="_blank">App Store</a>
        <a class="d-btn btn--outline app-link app-link--google" href="https://play.google.com/store/apps/details?id=com.appster.dentamatch" target="_blank">Google Play</a>
      </div>

      <a href="/signup" class="center-second__link">Dental practices sign up here </a>
    </div>
  </section>

  <section class="extra-features">
    <div class="d-container">
      <h2 class="extra-features__title">Features</h2>
      <b class="lead-text">Think of us as a hassle-free temp agency you can fit in your pocket.</b>
      <img src="/assets/img/svg/girl-with-phone.svg" alt="" class="extra-features__img">
      <div class="features-list">
        <div class="features-list__item">
          <span>1</span>
          <p>Set Your Own Schedule: Fill in your profile calendar with the days you want to work, or look for a full-time position</p>
        </div>
        <div class="features-list__item">
          <span>2</span>
          <p>Select Your Skills: Update your skills and experience in your personal profile and we’ll match them to dental offices where they’re most in demand</p>
        </div>
        <div class="features-list__item">
          <span>3</span>
          <p>In-App Messaging: Chat directly with dental office employers and avoid guessing games on where to park or put your lunch.</p>
        </div>
        <div class="features-list__item">
          <span>4</span>
          <p>Build Your Profile: Keep track of past and future bookings and earn kudos from past employers that boost your ranking in the app</p>
        </div>
      </div>

    </div>

  </section>

  <section class="extra-promo">
    <div class="d-container">
      <div class="extra-promo__title">Need extra cash? <br>
        Pick up extra work when you decide.
      </div>
      <ul class="extra-promo__list">
        <li class="extra-promo__item">
          On days when your office is normally closed.
        </li>
        <li class="extra-promo__item">
          Keep yourself booked with extra work even when your boss is on vacation.
        </li>
        <li class="extra-promo__item">
          Pick up extra days when you are in between jobs.
        </li>
      </ul>
      <img src="/assets/img/promo-img-c.png" alt="" class="extra-promo__img" width="516" height="889">
    </div>

    <div class="cta">
      <b>DentaMatch helps you keep your work life flexible. <br> Work when you want, on your schedule.</b>
      <a class="d-btn btn--gradient app-link app-link--apple" href="https://itunes.apple.com/us/app/dentamatch/id1185779291" target="_blank">App Store</a>
      <a class="d-btn btn--gradient app-link app-link--google" href="https://play.google.com/store/apps/details?id=com.appster.dentamatch" target="_blank">Google Play</a>
    </div>
  </section>


</main>

<footer class="main-footer">
  <div class="d-container">
    <ul class="social-links">
      <li class="social-link social-link--facebook"><a href="https://www.facebook.com/pg/dentalpositions/posts/" target="_blank">Facebook</a></li>
    </ul>

    <p class="copyright">
      © DentaMatch <?= date('Y') ?>
    </p>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</body>
</html>
