<!--Seeker listing-->
@foreach ($seekersList as $job)
<!--search preference list-->
<div class="media jobCatbox">
    <div class="media-left ">
        <div class="img-holder ">
          <img class="media-object img-circle" src="{{asset($job['profile_pic'])}}" alt="...">
            <span class="star star-fill"></span>
        </div>
    </div>
    <div class="media-body ">
        <div class="template-job-information mr-t-15">
          <div class="template-job-information-left">
            <h4 class="pull-left">{{$job['first_name'].' '.$job['last_name']}}</h4><span class="mr-l-5 label label-warning">3.8</span>
          </div>
          <div class="template-job-information-right">
            <span >2 miles away</span>
          </div> 
        </div> 
        <div class="job-type-detail">
            <p class="nopadding">Dental Assistant</p>
            <span class="bg-ember statusBtn mr-r-5">Temporary</span>
            <span class="dropdown date-drop">
                <span class=" dropdown-toggle"  data-toggle="dropdown">
                    <span class="day-drop">Friday, 08 Nov 2016</span>
                    <span class="caret"></span>
                </span>
                <ul class="dropdown-menu">
                    <li>Saturday, 09 Nov 2016</li>
                    <li>Sunday, 10 Nov 2016</li>
                    <li>Monday, 11 Nov 2016</li>
                </ul>
            </span>
        </div>

        <dl class="dl-horizontal text-left mr-t-30">
            <dt>Software Training:</dt>
            <dd>Softdent front office/Softdent charting</dd>

            <dt>CAD CAM:</dt>
            <dd>Planscan, Cerec</dd>

            <dt>DIG IMP:</dt>

            <dd>3 m true definition, 3 shape trios, Itero</dd>
            <dt>Digital Imaging:</dt>
            <dd>Dexis, Schick, Trophy, Suni</dd>
            <dt>Professional Training:</dt>
            <dd>Back office management, Supply inventory management</dd>
            <dt>Language:</dt>
            <dd>English, Spanish, Farsi</dd>
            <dt>General Skills:</dt>
            <dd>Intra oral camera, Alginate impression, Night guards</dd>
        </dl>
        <div class="row">
            <div class="col-sm-6 col-xs-6">
                <a href="#">See more.. </a>
            </div>
            <div class="col-sm-6 col-xs-6 ">
                <button type="submit" class="btn btn-primary pull-right pd-l-30 pd-r-30 ">Invite</button>
            </div>
        </div>
    </div>
</div>
<!--/search preference list-->
@endforeach 
