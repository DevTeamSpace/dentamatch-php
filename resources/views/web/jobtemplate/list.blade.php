@extends('web.layouts.dashboard')

@section('content')


  <div class="container pd-t-30">
    <button type="button" class="deleteTemplate btn btn-link-noline" data-toggle="modal" data-target="#show-help">
      <span class="fa fa-question-circle"></span>
      About this page
    </button>
    <div class="equalheight">
      <div class="col-sm-12  container-color border-block offset30">
        <div class="box text-center center-block">
          <a href="{{ url('jobtemplates/create') }}">
            <div class="info-block"><!-- BODY BOX-->
              <img src="{{asset('web/images/dentamatch-plussign.png')}}" alt="denta match plus sign" width="45">
            </div>
          </a>
          <div class="info-block"><!-- BODY BOX-->
            <span class="info-block-text">Create New Job Listing Template</span>
          </div>

        </div>
      </div>
      @foreach ($jobTemplates as $template)
        <div class="col-sm-12  container-color offset30 mainTemplateBlock">
          <div class="dropdown icon-upload-ctn">
            <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown"></span>
            <ul class="actions text-left dropdown-menu">
              <li class="text-center">
                <button type="button" class="deleteTemplate btn btn-link-noline" data-toggle="modal"
                        data-target="#discardTemplate" data-templateId="{{ $template['id'] }}">
                            <span class="delete-icon">
                                <img src="{{asset('web/images/dentamatch-delete.png')}}" alt="delta delete icon"
                                     width="11">
                            </span>
                  Delete
                </button>
                </form>
              </li>
            </ul>
          </div>
          <div class="defaultBlock box text-center center-block" id="job-recruitement">
            <div class="info-block"><!-- BODY BOX-->
              <img src="{{asset('web/images/dentamatch-folder.png')}}" alt="Denta match plus sign" width="37">
            </div>
            <div class="info-block"><!-- BODY BOX-->
              <div class="info-block-rec-text"><span>{{ $template['template_name'] }}</span></div>
              <div class="info-block-dental-text"><span>{{ $template['jobtitle_name'] }}</span></div>
            </div>

          </div>
          <div class="hoverBlock box-last text-center center-block hide" id="job-recruitement-create">
            <div class="info-block"><!-- BODY BOX-->
              <div class="info-block-rec-text"><span>{{ $template['template_name'] }}</span></div>
              <div class="info-block-dental-text"><span>{{ $template['jobtitle_name'] }}</span></div>
            </div>
            <div class="info-block"><!-- BODY BOX-->
              <div class="createjob">
                <a class="btn btn-primary btn-block" href="{{ url('createJob/'.$template['id']) }}">Post This Job</a>
              </div>
              <div class="info-block-dentinal-text">
                    <span class="view-template-text">
                        <a href="{{ url('jobtemplates/view/'.$template['id']) }}">Review Listing Template</a>
                    </span>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div id="discardTemplate" class="modal fade" role="dialog" style="display: none;">
    <div class="modal-dialog custom-modal popup-wd522">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Delete Template</h4>
        </div>
        <div class="modal-body text-center">
          <p>Do you want to delete this template?</p>
          <div class="mr-t-20 mr-b-30">
            <form method="post" action="{{ url('jobtemplates/delete/') }}">
              {!! csrf_field() !!}
              <input name="_method" type="hidden" value="DELETE">
              <input id="templateId" name="templateId" type="hidden" value="">

              <button type="submit" class="btn btn-primary pd-l-30 pd-r-30">Yes</button>
              <button id="cancelButton" type="button" class="btn btn-link mr-r-5" data-dismiss="modal">No</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="show-help" tabindex="-1" role="dialog" aria-labelledby="Show Help" aria-hidden="true">
    <div class="modal-dialog custom-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">About position templates</h4>
        </div>
        <div class="modal-body text-center">
         <ul class="text-center" style="list-style: disc; display: inline-block">
           <li class="text-left">Create a profile for each of the job positions you have in your office.</li>
           <li class="text-left">When you need to fill a position simply grab from your position profiles.</li>
           <li class="text-left">Indicate whether it's for full-time, part-time or temp and post. <br> It's that simple!</li>
         </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    $('.deleteTemplate').click(function () {
      $('#templateId').val($(this).data('templateid'));
    });
    $('.info-block img').on("mouseenter", function () {
      $(this).closest('div.mainTemplateBlock').children('div.defaultBlock').addClass('hide');
      $(this).closest('div.mainTemplateBlock').children('div.hoverBlock').removeClass('hide');
    });
    $('.mainTemplateBlock').on("mouseleave", function () {
      $(this).closest('div.mainTemplateBlock').children('div.defaultBlock').removeClass('hide');
      $(this).closest('div.mainTemplateBlock').children('div.hoverBlock').addClass('hide');

    });

    @if($showHelp)
      $('#show-help').modal('show');
    @endif
  </script>
@endsection
