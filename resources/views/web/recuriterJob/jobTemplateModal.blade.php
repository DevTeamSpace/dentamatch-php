@if(!empty($jobTemplateModalData))
<div id="jobTemplate" class="modal fade select_list " role="dialog">
    <div class="modal-dialog custom-modal popup-wd522">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Job</h4>
            </div>
            <div class="modal-body ">
                <div class="form-group custom-select">
                    <label for="templateModal">Choose the template</label>
                    <select  id="templateModal" name="templateModal"  class="selectpicker" required="" data-parsley-required-message="Please select the template." >
                        <option value="" disabled selected>Select </option>
                        @foreach($jobTemplateModalData as $template)
                        <option value="{{$template['id']}}" data-content="{{$template['template_name']}}">
                            {{$template['template_name']}}
                        </option>
                        <option data-divider="true"></option>
                        @endforeach
                        <option value="_CREATE_" data-content="[Create New Template]">
                            [Create New Template]
                        </option>
                        <option data-divider="true"></option>
                    </select>
                </div>
                <div class="text-right mr-t-20 mr-b-30">
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<script>
    $('#templateModal').change(function(e) {
        urlTemplateJob = "{{ url('createJob') }}";
        createTemplateUrl = "{{ url('jobtemplates/create') }}";
        templateId = $(this).val();
        templateRedirectUrl = urlTemplateJob+'/'+templateId;
        if(templateId == "_CREATE_")
        {
            templateRedirectUrl = createTemplateUrl;
        }
        window.location = templateRedirectUrl;
    });
</script>