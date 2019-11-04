<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="{{asset('/css/landing.css?v=2')}}">

    <title>DentaMatch</title>
</head>
<body>

<header class="d-container main-header">
    <img src="/assets/img/logo/group.png"
         srcset="/assets/img/logo/group@2x.png 2x,
             /assets/img/logo/group@3x.png 3x"
         class="main-logo" width="162" height="30" alt="DentaMatch logo">

    <img src="/assets/img/logo/group-10@3x.png" class="main-logo--big" width="323" height="60" alt="DentaMatch logo">

    <a href="/login" class="main-header__link">Dental practices login here </a>
</header>
<main>
    <section class="center">
        <div class="d-container">
            <h1 class="visually-hidden">DentaMatch</h1>
            <b class="lead-text">You work hard enough. <br> We make it easier. </b>
            <span class="center-sub-title">Matching dental professionals with temp, part-time, and full-time work </span>

            <div class="full-width">
              <img src="/assets/img/center/bg-mini.jpg" alt="" class="main-img--mobile">
            </div>

          <div class="center__links">
            <a href="/signup" class="d-btn btn--gradient">Dental practices sign up here </a>
              <span class="delimeter"></span>
            <a href="/jobseeker/signup" class="d-btn btn--outline">Job seekers sign up here</a>
          </div>

        </div>
    </section>

    <section class="approach">
        <div class="d-container">
            <h2 class="approach-title">Our hassle-free approach</h2>
            <b class="lead-text">A simple way to boost your team performance.</b>
            <ul class="approach-list">
                <li class="approach-item">
                    <img src="/assets/img/approach/search.svg" alt="">
                    <b>Skill-based matches</b>
                    <span>Find a good fit without <br> resumes or middlemen</span>
                </li>
                <li class="approach-item">
                    <img src="/assets/img/approach/menu-2.svg" alt="">
                    <b>Simplified schedule</b>
                    <span>Track every who, what, and when from one central calendar</span>
                </li>
                <li class="approach-item">
                    <img src="/assets/img/approach/like.svg" alt="">
                    <b>Ready when you are</b>
                    <span>Great work and great workers <br> are always a few clicks away</span>
                </li>
            </ul>
        </div>
    </section>

    <section class="practice-info">
        <div class="d-container practice-info__container">
            <h3 class="practice-title">I'm a Dental Practice </h3>
            <b class="lead-text">Find skilled dental professionals—anytime.</b>
            <span>We just launched in select cities, including San Francisco. Create your office profile today!</span>
            <div class="practice-video">
                <div class="practice-video__wrapper">
                    <iframe frameborder="0" src="https://player.vimeo.com/video/207225121" width="640" height="360" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            </div>
            <a href="/signup" class="practice-link">Dental practices sign up here </a>
        </div>
    </section>

    <section class="practice-features">
        <div class="d-container">
            <h3 class="lead-text">Everything your dental practice needs</h3>
            <div class="practice-slider">
                <!--<ul class="practice-slider__nav">-->
                <!--<li>Build a custom profile</li>-->
                <!--<li>Find the right people</li>-->
                <!--<li>Know who to expect and when</li>-->
                <!--<li>Communicate directly</li>-->
                <!--</ul>-->

                <div class="practice-slider__slides">
                    <div >
                        <div class="practice-slider__slide">
                            <img src="/assets/img/practice-slider/1.png" alt="" class="practice-slider__img">
                            <div class="practice-slider__info">
                                <div class="practice-slider__title">Build a custom profile</div>
                                <div class="practice-slider__text">List new and reoccurring jobs in your practice profile to activate a posting and review candidates in seconds
                                </div>
                            </div>
                        </div>
                    </div>
                    <div >
                        <div class="practice-slider__slide">
                            <img src="/assets/img/practice-slider/2.png" alt="" class="practice-slider__img">
                            <div class="practice-slider__info">
                                <div class="practice-slider__title">Find the right people</div>
                                <div class="practice-slider__text">Our matches are based on location, schedule, and skills—so you’ll always get the most qualified candidates available</div>
                            </div>
                        </div>
                    </div>
                    <div >
                        <div class="practice-slider__slide">
                            <img src="/assets/img/practice-slider/3.png" alt="" class="practice-slider__img">
                            <div class="practice-slider__info">
                                <div class="practice-slider__title">Know who to expect and when</div>
                                <div class="practice-slider__text">A central calendar view makes it easy to keep track of open offers and upcoming bookings</div>
                            </div>
                        </div>
                    </div>
                    <div >
                        <div class="practice-slider__slide">
                            <img src="/assets/img/practice-slider/4.png" alt="" class="practice-slider__img">
                            <div class="practice-slider__info">
                                <div class="practice-slider__title">Communicate directly</div>
                                <div class="practice-slider__text">There’s no middleman here. Send job candidates questions and  booking requests with in-app messaging</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="practice-slider__btns">
                    <span class="practice-slider__btn practice-slider__btn-prev"></span>
                    <span class="practice-slider__btn practice-slider__btn-next"></span>
                </div>
            </div>

            <p class="practice-features__text">Like what you see? Start saving time and money!</p>

            <a href="/signup" class="d-btn btn--gradient">Let's get started</a>
        </div>
    </section>

    <section class="professional-info">
        <div class="d-container professional-info__container">
            <h3 class="professional-title">I'm a Dental Professional</h3>
            <b class="lead-text lead-text--invert">Find work that fits your skills—and your schedule.</b>
            <span>We just launched in select cities, including San Francisco. Get early access today!</span>
            <div class="professional-video">
                <div class="professional-video__wrapper">
                    <iframe frameborder="0" src="https://player.vimeo.com/video/207224967" width="640" height="360" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            </div>
            <a href="/jobseeker/signup" class="professional-link">Job seekers sign up here</a>
        </div>
    </section>

    <section class="professional-features">
        <div class="d-container">
            <h3 class="lead-text lead-text--invert professional-features__title hide-lg">Everything you need, with no extra fees</h3>
            <div class="professional-slider">
                <h3 class="lead-text lead-text--invert professional-features__title show-lg">Everything you need to find your job is just a few clicks away.</h3>
                <div class="professional-slider__slides">
                    <div >
                        <div class="professional-slider__slide">
                            <img src="/assets/img/pro-slider/1.jpg" alt="" class="professional-slider__img">
                            <div class="professional-slider__info">
                                <div class="professional-slider__title">Set your own schedule</div>
                                <div class="professional-slider__text">Fill in your profile calendar with the days you want
                                    to work, or look for a full-time position
                                </div>
                            </div>
                        </div>
                    </div>
                    <div >
                        <div class="professional-slider__slide">
                            <img src="/assets/img/pro-slider/2.jpg" alt="" class="professional-slider__img">
                            <div class="professional-slider__info">
                                <div class="professional-slider__title">Better than a resume</div>
                                <div class="professional-slider__text">Just list your skills and experience in your personal
                                    profile to find the dental offices where they’re most in demand
                                </div>
                            </div>
                        </div>
                    </div>
                    <div >
                        <div class="professional-slider__slide">
                            <img src="/assets/img/pro-slider/3.jpg" alt="" class="professional-slider__img">
                            <div class="professional-slider__info">
                                <div class="professional-slider__title">Get all the details</div>
                                <div class="professional-slider__text">Avoid guessing games on where to park or put your
                                    lunch with in-app messaging
                                </div>
                            </div>
                        </div>
                    </div>
                    <div >
                        <div class="professional-slider__slide">
                            <img src="/assets/img/pro-slider/4.jpg" alt="" class="professional-slider__img">
                            <div class="professional-slider__info">
                                <div class="professional-slider__title">Build your profile</div>
                                <div class="professional-slider__text">Keep track of past and future bookings and earn kudos
                                    from past employers that boost your ranking in the app
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="professional-slider__btns">
                    <span class="professional-slider__btn professional-slider__btn-prev"></span>
                    <span class="professional-slider__btn professional-slider__btn-next"></span>
                </div>

                <div class="professional-features__link">
                    <a href="/jobseeker/signup" class="professional-link ">Learn more</a>
                </div>
            </div>
        </div>
    </section>

    <section class="main-app-links">
         <a class="d-btn btn--gradient app-link app-link--apple" href="https://itunes.apple.com/us/app/dentamatch/id1185779291" target="_blank">App Store</a>
         <a class="d-btn btn--gradient app-link app-link--google" href="https://play.google.com/store/apps/details?id=com.appster.dentamatch" target="_blank">Google Play</a>
    </section>

    <section class="team">
        <div class="d-container">
            <h3 class="lead-text">Our Team</h3>
            <ul class="team-list">
                <li class="team-item">
                    <img src="/assets/img/team/photo-1@2x.jpg"
                         srcset="/assets/img/team/photo-1@2x.jpg 2x,
                             /assets/img/team/photo-1@3x.jpg 3x"
                         alt="Dr. Preston Brown"
                         class="team-item__photo">
                    <p class="team-item__text"><strong>Dr. Preston Brown</strong> was born and raised in Ogden Utah.
                        After graduating from the University Of Washington School Of Dentistry in 1998 he moved to Palo Alto,
                        CA to do a residency program at the Veterans Hospital. <br>
                        Dr. Brown started his own private practice 20 years ago in the Financial District of San Francisco.
                        His professional career was profoundly influenced by his 10 year commitment to the Marin County Public Dental <Clinic class="br"></Clinic>
                        It's his belief that DentaMatch will streamline inefficiencies, save money, and create a space where everyone is more prepared, educated and informed about their work choices so that in the end, the patient receives the best care possible.</p>
                    <span class="btn-read-more">Read more</span>
                    <span class="btn-read-more btn-read-more--up">Roll Up</span>
                </li>

                <li class="team-item">
                    <img src="/assets/img/team/photo-1-copy@2x.jpg"
                         srcset="/assets/img/team/photo-1-copy@2x.jpg 2x,
                             /assets/img/team/photo-1-copy@3x.jpg 3x"
                         alt="Jeffrey Mortensen"
                         class="team-item__photo">
                    <p class="team-item__text"><strong>Jeffrey Mortensen</strong> has had successful tenures at multiple healthcare product companies.  Most recently he was with Patterson Dental for 11+ years providing consulting services and solutions to dental practices.
                      <br> Jeff is an industry expert with experience in optimizing workflow systems and the creation of successful go-to-market strategies.
                      The development of DentaMatch is in response to the overwhelming demand for an easier, faster more economic solution for sourcing talent specific to the needs of the dental practice.
                      <br> Jeff currently resides in Los Angeles.</p>
                    <span class="btn-read-more">Read more</span>
                    <span class="btn-read-more btn-read-more--up">Roll Up</span>
                </li>

                <li class="team-item">
                    <img src="/assets/img/team/photo-1-copy-2@2x.jpg"
                         srcset="/assets/img/team/photo-1-copy-2@2x.jpg 2x,
                             /assets/img/team/photo-1-copy-2@3x.jpg 3x"
                         alt="Matt Belitsky"
                         class="team-item__photo">
                    <p class="team-item__text"><strong>Matt Belitsky</strong> worked as a Salesforce consultant for 10+ years helping rapidly growing startups scale to prove a repeatable revenue model.
                      <br> Recently, he’s tackled the post-Sales side of the business as a thought leader and executive focused on retention and expansion. He joins DentaMatch as a growth expert to quickly activate hot markets, building marketplace inventory- both talent and offices.
                      <br>He’s based in San Francisco.</p>
                    <span class="btn-read-more">Read more</span>
                    <span class="btn-read-more btn-read-more--up">Roll Up</span>
                </li>
            </ul>
        </div>
    </section>

    <section class="cta">
        <b>Like what you see? Start saving time and money!</b>
        <div class="cta__links">
            <a href="/signup" class="d-btn btn--gradient">Dental practices sign up here </a>
            <a href="/jobseeker/signup" class="d-btn btn--outline">Job seekers sign up here</a>
        </div>
    </section>


</main>

<footer class="main-footer">
    <div class="d-container">
        <ul class="social-links">
            <li class="social-link social-link--facebook"><a href="https://www.facebook.com/pg/dentalpositions/posts/" target="_blank">Facebook</a></li>
        </ul>

        <p class="copyright">
            © DentaMatch 2019
        </p>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

@include('landing._promo')

<script>
  $(function(){
    $('.professional-slider__slides').slick({
      slidesToShow: 1,
      accessibility: false,
      fade: true,
      prevArrow: '.professional-slider__btn-prev',
      nextArrow: '.professional-slider__btn-next',
    });

    $('.practice-slider__slides').slick({
      slidesToShow: 1,
      accessibility: false,
      // fade: true,
      prevArrow: '.practice-slider__btn-prev',
      nextArrow: '.practice-slider__btn-next',
    });

    $('.btn-read-more').click(function () {
      $(this).closest('li').toggleClass('team-item--full-text');
    })
  });
</script>

</body>
</html>
