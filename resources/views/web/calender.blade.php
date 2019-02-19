@extends('web.layouts.dashboard')

@section('content')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <div class="container mr-t-30" id="calenderParent">
    <!-- Modal -->
    <div class="modal fade calendar_list" role="dialog">
      <div class="modal-dialog custom-modal popup-wd522">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" data-bind="text: jobCreated"></h4>
          </div>
          <div class="modal-body content mCustomScrollbar light" data-mcs-theme="minimal-dark">
            <!--ko foreach: allJobs-->
            <div class="panel ">
              <a data-toggle="modal" data-dismiss="modal" class="panel-body" data-bind="click: $root.showSeekers">
                <div class="calender-list-title" data-bind="text: poptitle"></div>
                <div class="seeker-list">
                  <!--ko foreach: userDetails-->
                  <img src="http://placehold.it/28x28" onerror="this.src = 'http://placehold.it/28x28'"
                       data-bind="attr: {src: pic}" class="img-circle cir-28">
                  <!--/ko-->
                </div>
              </a>
            </div>
            <!--/ko-->
          </div>
        </div>
      </div>
    </div>

    <div id="calendar" class="dev_calender_div"></div>

    <!-- Modal -->
    <div class="modal fade calendar_brief" role="dialog">
      <div class="modal-dialog custom-modal popup-wd522">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" data-bind="text: jobCreated"></h4>
          </div>
          <div class="modal-body content mCustomScrollbar light
                " data-mcs-theme="minimal-dark">
            <div class="row">
              <div class="col-xs-8 cal-detail-address">
                <div class="job-title" data-bind="text: particularJobTitle"></div>
                <h5 data-bind="text: particularOfficeName"></h5>
                <span data-bind="text: particularOfficeTypeName"></span>
                <p data-bind="text: particularOfficeAddress"></p>
              </div>
              <div class="col-xs-4">
                <a type="button" class="btn btn-primary pull-right" data-bind="attr: { href: particularJobUrl }">View
                  Details</a>
              </div>
            </div>
            <div class="cal-hired-seeker mr-t-20">
              Hired Talent
            </div>
            <div class="row flex-row">
              <!--ko foreach: seekersOfParticularJob-->
              <div class="col-xs-12 col-sm-6">
                <a data-bind="attr: { href: seekerUrl }" class="">
                  <div class="media">
                    <div class="media-left ">
                      <img src="http://placehold.it/28x28" onerror="this.src = 'http://placehold.it/28x28'"
                           data-bind="attr: {src: seekerPic}" class="img-circle cir-55">
                    </div>
                    <div class="media-body pd-t-10">
                      <h4 class="media-heading" data-bind="text: seekerName">Paula Jackson</h4>
                      <p data-bind="text: seekerJobTitle"></p>
                    </div>
                  </div>
                </a>
              </div>
              <!--/ko-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('js')
  <script>

  </script>
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
  <script src="{{asset('web/scripts/calender.js')}}"></script>
@endsection