@extends('web.layouts.dashboard')

@section('content')


        <div class="container padding-top-30">
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
                <div class="col-sm-12 nopadding container-color offset30 mainTemplateBlock">
                    <div class="dropdown icon-upload-ctn">
                        <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown"></span>
                        <ul class="actions text-left dropdown-menu">
                            <li class="text-center">
                                <a href="{{ url('jobtemplates/delete/'.$template['id']) }}">
                                <span class="delete-icon">
                                    <img src="{{asset('web/images/dentamatch-delete.png')}}" alt="delta delete icon" width="10">
                                </span>
                                <span class="cancel">Delete</span>
                                </a>
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
@endsection

@section('js')
        <script>
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
