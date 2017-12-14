@extends('web.layouts.dashboard')

@section('content')
<div class="container">
<div class="dashboarFinalBox mr-t-25">

<div class="alert alert-info customInfo">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<div class="infoContent">
<h3>Announcements</h3>
It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.
</div>
</div>

<div class="dashboarFinalInnerBox">
<div class="row">
<div class="col-sm-6">
<div class="commonBox welcomeLeft">
<div class="welcomeLeftheader">
<h6>WELCOME</h6>
<h4>Smiley Care</h4>
<a href="#" id="dashEdit">Edit profile</a>
</div>
<div class="welcomeContent">
<h4>Monday<br>August 03, 2017</h4>
<div class="tHire">
 <span>02</span>   
 <p>Today’s Hire</p>
</div>

<ul class="dashboarFinalList"> 
<li>
<div class="dashListImgBlock">
<div class="dashListImg"></div>
<div class="dashListImgContent">
  <h6>Elle Nelson</h6>
  <p>Dental Assistant</p>  
</div>
<a href="#" class="dashListRightPos">View Job details</a>
</div>
<div class="line"></div>
</li>
<li>
<div class="dashListImgBlock">
<div class="dashListImg"></div>
<div class="dashListImgContent">
  <h6>Elle Nelson</h6>
  <p>Dental Assistant</p>  
</div>
<a href="#" class="dashListRightPos">View Job details</a>
</div>
<div class="line"></div>
</li>
</ul>

</div>



</div>



<div class="commonBox ">

<div class="welcomeContent lastMsg">
<h4>Latest Messages</h4>
<div class="tHire unread">
5 Unread
</div>

<ul class="dashboarFinalList"> 
<li>
<div class="dashListImgBlock">
<div class="dashListImg"></div>
<div class="dashListImgContent msgImg">
  <h6>Elle Nelson</h6>
  <p>Dental Assistant</p>  
</div>

<div class="msgNotficaitonBlock">
<span>Sep 4</span>
<div class="msgNotification ">4</div></div>
</div>

</li>
<li>
<div class="dashListImgBlock">
<div class="dashListImg"></div>
<div class="dashListImgContent msgImg">
  <h6>Elle Nelson</h6>
  <p>Dental Assistant</p>  
</div>
<div class="msgNotficaitonBlock">
<span>Sep 4</span>
<div class="msgNotification ">4</div></div>
</div>
</div>

</li>
</ul>
<div class="viewAll">View all</div>

</div>




<div class="commonBox">

<div class="welcomeContent lastMsg">
<h4>Latest Notifications</h4>
<div class="tHire unread">
3 Unread
</div>

<ul class="dashboarFinalList  latestNotification"> 
<li>
<div class="dashListImgBlock">
<div class="dashListImg"></div>
<div class="dashListImgContent msgImg">
<h6 class="media-heading">Media heading <p>Paula Jackson has applied for the Dental Hygienists</p></h6>
  <p class="justNow"><span class="icon-clock"></span>Just now</p> 
</div>

<div class="onlineDot border-radius">

</div>

<li>
<div class="dashListImgBlock">
<div class="dashListImg"></div>
<div class="dashListImgContent msgImg">
<h6 class="media-heading">Media heading <p>Paula Jackson has applied for the Dental Hygienists</p></h6>
  <p class="justNow"><span class="icon-clock"></span>Just now</p> 
</div>

<div class="onlineDot border-radius">

</div>

</li>

</ul></div>



<div class="viewAll">View all</div>

</div>



</div>





<div class="col-sm-6">
<div class="commonBox">
<div class="welcomeContent lastMsg">
<h4>What to do next?</h4>
<ul class="dashboadPostBlock">
<li>
  <a href="#"><img src="http://dev.dentamatch.com/web/images/dentamatch-folder.png" width="45"></a>
  Post New Job
</li>
<li>
  <a href="#"><img src="http://dev.dentamatch.com/web/images/dentamatch-plussign.png" width="45"></a>
  Create Template
</li>
<li>
  <a href="#"><img src="http://dev.dentamatch.com/web/images/dentamatch-foldercurrentJob.png" width="45"></a>
  View Current Jobs
</li>

</ul>

</div>
</div>



<div class="commonBox">
<div class="welcomeContent lastMsg">
<h4>This week’s view</h4>
<ul class="weekList">
<li class="weekActive">Aug 3 - Mon</li>
<li>Aug 4 -  TUE
<div class="dental">
  <p>Dental Assistant</p>
  <div class="dentalImg">
    <img src="http://dev.dentamatch.com/web/images/defaultImg.png" width="22" class="img-circle">
    <img src="http://dev.dentamatch.com/web/images/defaultImg.png" width="22" class="img-circle">
    <img src="http://dev.dentamatch.com/web/images/defaultImg.png" width="22" class="img-circle">
  </div>
</div>

</li>
<li>Aug 5 - WED  
<div class="dental">
  <p>Dental Hygienist</p>
  <div class="dentalImg">
    <img src="http://dev.dentamatch.com/web/images/defaultImg.png" width="22" class="img-circle">
    <img src="http://dev.dentamatch.com/web/images/defaultImg.png" width="22" class="img-circle">
   
    <div class="dentalNumber img-circle">2+</div>
    
  </div>
</div>
<a href="#" class="moreJobs pull-right">4 More Jobs</a>

</li>
<li>Aug 6 - THU  </li>
<li>Aug 7 - FRI </li>
<li class="weekHolidday">Aug 8 - SAT </li>
<li class="weekHolidday">Aug 9 - SAN </li>


</ul>
<div class="viewAll">View calendar</div>

</div>
</div>


<div class="commonBox">

<div class="welcomeContent lastMsg">
<h4>Recently Posted Jobs</h4>


<ul class="dashboarFinalList recentlyPost "> 
<li>
<div class="template-job-information ">
                <div class="template-job-information-left">
                    <h4>Dental Hygienist</h4>
                </div>
              
            </div>
            <div class="job-type-detail">
                <div class="job-type-info">
                    <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
                    <span> | Wednesday, Friday</span>
                </div>
            </div>
        

<div class="postViewDetail text-right">View details</div>

</li>

<li>
<div class="template-job-information ">
                <div class="template-job-information-left">
                    <h4>Dental Hygienist</h4>
                </div>
            </div>
            <div class="job-type-detail">
                <div class="job-type-info">
                    <span class="bg-ember statusBtn mr-r-5">Temporary</span>
                    <span class="dropdown date-drop">
						<span class=" dropdown-toggle" data-toggle="dropdown"><span class="day-drop">Friday, 08 Nov 2016</span>
                    <span class="caret"></span></span>
                    <ul class="dropdown-menu">
                        <li>Saturday, 09 Nov 2016</li>
                        <li>Sunday, 10 Nov 2016</li>
                        <li>Monday, 11 Nov 2016</li>
                    </ul>
                    </span>
                </div>
            </div>
            <div class="postViewDetail text-right">View details</div>



</li>

</ul></div>



<div class="viewAll">View all</div>

</div>






</div>








</div>



</div>





</div>
</div>

@endsection
@section('js')
@endsection