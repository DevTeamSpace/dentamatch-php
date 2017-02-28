@extends('web.layouts.dashboard')

@section('content')
<style>
    .pac-container:after{
        content:none !important;
    }
</style>
<div class="container globalpadder">
    <!-- Tab-->
    <div class="row">
        @include('web.layouts.sidebar')
        <div class="col-sm-8 ">
            <div class="addReplica">
                
                <form data-parsley-validate="" id="createProfileForm" novalidate=""  class="formdataPart" action="javascript:void(0);">	
                    <div class="resp-tabs-container commonBox profilePadding cboxbottom ">
                        <div class="descriptionBox">
                            <div class="dropdown icon-upload-ctn1">
                                <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                                <ul class="actions text-left dropdown-menu">
                                    <li><span class="gbllist"><i class="icon icon-edit"></i> Edit</span></li>
                                </ul>
                            </div>
                            <div class="viewProfileRightCard">
                                <div class="detailTitleBlock">
                                    <h5>SMILLEY CARE</h5>
                                </div>
                                <h6>Dental Office Description</h6>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quorum sine causa fieri nihil putandum est. Potius inflammat, ut coercendi magis quam dedocendi esse videantur. Duo Reges: constructio interrete. Mihi enim satis est, ipsis non satis. Cuius ad naturam apta ratio vera illa et summa lex a philosophis dicitur. Id est enim, de</p>
                            </div>
                        </div>
                    </div>
                </form>
                

                <form data-parsley-validate="" id="officedetailform" novalidate=""  class="formdataPart">	
                    <div class="resp-tabs-container commonBox replicaBox profilePadding cboxbottom masterBox">
                        <div class="descriptionBox">
                            <div class="dropdown icon-upload-ctn1">
                                <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                                <ul class="actions text-left dropdown-menu">
                                    <li><span class="gbllist"><i class="icon icon-edit "></i> Edit</span></li>
                                    <li><span class="gbllist"><i class="icon icon-deleteicon"></i> Delete</span></li>
                                </ul>
                            </div>
                            <div class="descriptionBoxInner">
                                <div class="viewProfileRightCard pd-b-25">
                                    <div class="detailTitleBlock">
                                        <h5>OFFICE DETAILS</h5>
                                    </div>
                                    <h6>Dental Office Type</h6>
                                    <p>Dental Office Type</p>
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Dental Office Address</h6>
                                    <p>Smiley Care, 910 South 17th Street, Newark, New York 07108, USA</p>
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Phone Number</h6>
                                    <p>(415) 200 - 2356</p>
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Working Hours</h6>
                                    <p>Monday : 9am to 6pm</p>
                                    <p>Monday : 9am to 6pm</p>
                                    <p>Monday : 9am to 6pm</p>
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Office Location Information</h6>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quorum sine causa fieri nihil putandum est. Potius inflammat, ut coercendi magis quam dedocendi esse videantur. Duo Reges: constructio interrete. Mihi enim satis est, ipsis non satis. Cuius ad naturam apta ratio vera illa et summa lex a philosophis dicitur. Id est enim, de quo quaerimus. Id quaeris, inquam, in quo, utrum respondero, verses te huc atque illuc necesse est. Sed ad bona praeterita redeamus. Roges enim Aristonem, bonane ei videantur haec: vacuitas doloris, divitiae, valitudo; Nam prius a se poterit quisque discedere quam appetitum earum rerum, quae sibi conducant, amittere.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <div class="pull-right text-right">
                <div class="addProfileBtn "><span class="icon icon-plus"></span>Add total of 1 locations</div>
            </div>
        </div>
    </div>
</div>    
<!--<input id="autocomplete" type="text">
<input id="postal_code" type="text">-->
@section('js')

<script>
    var placeSearch, autocomplete;
    var componentForm = {
        postal_code: 'short_name'
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        console.log(document.getElementById('autocomplete'));
        autocomplete = new google.maps.places.SearchBox(
                /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
                {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('places_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var allPlace = autocomplete.getPlaces();

        allPlace.forEach(function (place) {
                    $('#postal_code').val('');
            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(addressType).value = val;
                }
            }
                    document.getElementById('full_address').value = place.formatted_address;
                    document.getElementById('lat').value = place.geometry.location.lat();
                    document.getElementById('lng').value = place.geometry.location.lng();
                    $('#autocomplete').val(place.formatted_address);
                    checkLocation($('#postal_code').val(), '');

        });

    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places"
async defer></script>
<script src="{{asset('web/scripts/edit-profile.js')}}"></script>
@endsection
@endsection
