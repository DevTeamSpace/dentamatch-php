@extends('web.layouts.dashboard')

@section('content')
<div class="container mr-b-60 padding-container-template">





    <div class="media jobCatbox">
        <div class="media-left ">
            <div class="img-holder">

                <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
            </div>
        </div>
        <div class="media-body row">
            <div class="col-sm-8 pd-t-10 ">
                <div ><a href="#" class="media-heading">Paula Jackson</a> <span class="mr-l-5 label label-success">3.8</span></div>
                <p>Dental Assistant</p>
            </div>
            <div class="col-sm-4 pd-t-15 text-right">
                <button type="submit" class="btn  btn-primary-outline active pd-l-30 pd-r-30 mr-b-5" data-toggle="modal" data-target=".select_list">Invite</button>
                <p class="text-success "><span  class=" invite-success"><i class="fa fa-check "></i></span> Invitation sent</p>
            </div>
        </div>
    </div>
    <div class="media jobCatbox">
        <div class="media-left ">
            <div class="img-holder">

                <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
            </div>
        </div>
        <div class="media-body row">
            <div class="col-sm-8 pd-t-10 ">
                <div ><a href="#" class="media-heading">Elle Nelson</a> <span class="mr-l-5 label label-success">3.4</span></div>
                <p>Dental Assistant</p>
            </div>
            <div class="col-sm-4 pd-t-15 text-right">
                <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
            </div>
        </div>
    </div>
    <div class="media jobCatbox">
        <div class="media-left ">
            <div class="img-holder">

                <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
            </div>
        </div>
        <div class="media-body row">
            <div class="col-sm-8 pd-t-10 ">
                <div ><a href="#" class="media-heading">James Fernandis</a> <span class="mr-l-5 label label-success">4.1</span></div>
                <p>Dental Assistant</p>
            </div>
            <div class="col-sm-4 pd-t-15 text-right">
                <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
            </div>
        </div>
    </div>
    <div class="media jobCatbox">
        <div class="media-left ">
            <div class="img-holder">

                <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
            </div>
        </div>
        <div class="media-body row">
            <div class="col-sm-8 pd-t-10 ">
                <div ><a href="#" class="media-heading">Anthony Palmer</a> <span class="mr-l-5 label label-warning">3.0</span></div>
                <p>Dental Assistant</p>
            </div>
            <div class="col-sm-4 pd-t-15 text-right">
                <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
            </div>
        </div>
    </div>
    <div class="media jobCatbox">
        <div class="media-left ">
            <div class="img-holder">

                <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
            </div>
        </div>
        <div class="media-body row">
            <div class="col-sm-8 pd-t-10 ">
                <div ><a href="#" class="media-heading">Suzanne Holroyd</a> <span class="mr-l-5 label label-warning">2.8</span></div>
                <p>Dental Assistant</p>
            </div>
            <div class="col-sm-4 pd-t-15 text-right">
                <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
            </div>
        </div>
    </div>






    <!-- Modal -->
    <div class="modal fade select_list " role="dialog">
        <div class="modal-dialog custom-modal popup-wd522">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invite Jobseeker</h4>
                </div>
                <div class="modal-body ">

                    <form data-parsley-validate>
                        <div class="form-group custom-select">
                            <label for="selectJobSeeker">Choose the job you want to invite for</label>
                            <select  id="selectJobSeeker"  class="selectpicker" required="" data-parsley-required-message="Please select the job." >
                                <option value="" disabled selected>Select </option>
                                <option value="" data-content="<h5>Dental Hygienist</h5><span class='label label-warning'>Temporary</span>">
                                    Dental Hygienist
                                </option>
                                <option data-divider="true"></option>
                                <option value="" data-content="<h5>Dental </h5><span class='label label-warning'>Temporary</span>">
                                    Dental
                                </option>

                            </select>

                        </div>

                        <div class="text-right mr-t-20 mr-b-30">
                            <button type="button" class="btn btn-link mr-r-20">Create Job</button>
                            <button type="submit" class="btn btn-primary pd-l-30 pd-r-30">Send</button>
                        </div>



                    </form>

                </div>


            </div>

        </div>
    </div>

</div>
<script>
		$(document).ready(function(){
			$('#selectJobSeeker, #selectTemplate').selectpicker({
				style: 'btn btn-primary'
			});

		});

	</script>
@endsection
