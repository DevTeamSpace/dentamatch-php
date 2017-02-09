@extends('web.layouts.dashboard')

@section('content')


        <div class="container pd-t-30">
            <div class="equalheight">
                <div class="col-sm-12 nopadding container-color border-block offset30" >
                    <div class="box text-center center-block">
                        <a href="{{ url('jobtemplates/create') }}">
                        <div class="info-block"><!-- BODY BOX-->
                            <img src="{{asset('web/images/dentamatch-plussign.png')}}" alt="denta match plus sign" width="45">
                        </div>
                        <div class="info-block"><!-- BODY BOX-->
                            <span class="info-block-text">Create Template</span>
                        </div>
                        </a>
                    </div>      
                </div> 
                @foreach ($jobTemplates as $template)
                <div class="col-sm-12  container-color offset30 mainTemplateBlock">
                    <div class="dropdown icon-upload-ctn">
                        <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown"></span>
                        <ul class="actions text-left dropdown-menu">
                            <li class="text-center">
                                    <button type="button" class="deleteTemplate btn btn-link-noline" data-toggle="modal" data-target="#discardTemplate" data-templateId="{{ $template['id'] }}">
                                        <span class="delete-icon">
                                            <img src="{{asset('web/images/dentamatch-delete.png')}}" alt="delta delete icon" width="10">
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
                                <button type="button" class="btn btn-primary">
                                    <a href="{{ url('createJob/'.$template['id']) }}">Create Job Opening</a>
                                </button>
                            </div>
                            <div class="info-block-dentinal-text"> 
                                <span class="view-template-text">
                                    <a href="{{ url('jobtemplates/view/'.$template['id']) }}">View Template</a>
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
                                                
                                                <button type="submit" class="btn btn-primary pd-l-30 pd-r-30" >Yes </button>
						<button id="cancelButton" type="button" class="btn btn-link mr-r-5" data-dismiss="modal">No</button>
                                            </form>
					</div>
                                </div>
			</div>
		</div>
	</div>
@endsection

@section('js')
        <script>
            $('.deleteTemplate').click(function(){
                var templateId = $(this).data('templateid');
                $('#templateId').val(templateId);
                console.log(templateId);
            });
            $('.info-block img').on( "mouseenter", function() {
                $(this).closest('div.mainTemplateBlock').children('div.defaultBlock').addClass('hide');
                $(this).closest('div.mainTemplateBlock').children('div.hoverBlock').removeClass('hide');
            });
            $('.mainTemplateBlock').on( "mouseleave", function() {
                 $(this).closest('div.mainTemplateBlock').children('div.defaultBlock').removeClass('hide');
                $(this).closest('div.mainTemplateBlock').children('div.hoverBlock').addClass('hide');
                
            });
            </script>
@endsection
