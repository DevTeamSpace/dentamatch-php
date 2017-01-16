@extends('web.layouts.dashboard')

@section('content')


<form data-parsley-validate method="post" action="{{url('create-profile')}}">
    {{ csrf_field() }}
    <div class="customContainer center-block containerBottom">
        <div class="profieBox">
            <h3>Create Profile</h3>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="commonBox cboxbottom">
                <div class="form-group">
                    <label >Dental Office Name</label>
                    <input type="text" value="{{ old('officeName') }}" name="officeName" class="form-control"  data-parsley-required data-parsley-required-message="office name required">
                </div>
                <div class="form-group">
                    <label  >Dental Office Description</label>
                    <textarea class="form-control  txtHeight" value="{{ old('officeDescription') }}" name="officeDescription"  data-parsley-required data-parsley-required-message="office description required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" ></textarea>
                </div>
            </div>		

            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Dental Office Type</label>
                    <div class="slt">
                        <select name="officeType" value="{{ old('officeType') }}" class="ddlCars" multiple="multiple" data-parsley-required data-parsley-required-message="office type required">
                            @foreach($officeType as $office)
                            <option value="{{$office->id}}">{{$office->officetype_name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label>Dental Office Address</label>
                    <input name="officeAddress" value="{{ old('officeAddress') }}" type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="office address required">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits" >
                </div>

                <div class="form-group">
                    <label >Working Hours</label>
                    <div class="row dayBox EveryDayCheck">
                        <div class="col-sm-4">  
                            <p class="ckBox">
                                <input type="checkbox" id="test2" name="everyday" value="{{ old('everyday') }}" />
                                <label for="test2" class="ckColor"> Everyday</label>
                            </p>    
                        </div>
                        <div class="col-sm-4"><input value="{{ old('everydayStart') }}" name="everydayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                        <div class="col-sm-4"><input value="{{ old('everydayEnd') }}" name="everydayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                    </div>

                    <div class="allDay">  
                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="mon" name="monday" value="{{ old('monday') }}" />
                                    <label for="mon" class="ckColor"> Monday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('mondayStart') }}" name="mondayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('mondayEnd') }}" name="mondayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="tue" name="tuesday" value="{{ old('tuesday') }}"  />
                                    <label for="tue" class="ckColor"> Tuesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('tuesdayStart') }}" name="tuesdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('tuesdayEnd') }}" name="tuesdayEnd" type="text" class="form-control" placeholder="Closing Hours"   data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="wed" name="wednesday" value="{{ old('wednesday') }}" />
                                    <label for="wed" class="ckColor"> Wednesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('wednesdayStart') }}" name="wednesdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('wednesdayEnd') }}" name="wednesdayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="thu" name="thrusday"  value="{{ old('thrusday') }}"  />
                                    <label for="thu" class="ckColor"> Thursday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('thrusdayStart') }}" name="thrusdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('thrusdayEnd') }}" name="thrusdayEnd" type="text" class="form-control" placeholder="Closing Hours" data-parsley-required-message="closing hours required"  ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="fri" name="friday" value="{{ old('friday') }}" />
                                    <label for="fri" class="ckColor"> Friday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('fridayStart') }}" name="fridayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('fridayEnd') }}" name="fridayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sat" name="saturday" value="{{ old('saturday') }}" />
                                    <label for="sat" class="ckColor"> Saturday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('saturdayStart') }}" name="saturdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('saturdayEnd') }}" name="saturdayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sun" name="sunday" value="{{ old('sunday') }}" />
                                    <label for="sun" class="ckColor"> Sunday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('sundayStart') }}" name="sundayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('sundayEnd') }}" name="sundayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>
                    </div>
                </div>	
                <div class="form-group">
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea class="form-control txtHeight"   data-parsley-required-message="location information required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" ></textarea>
                </div>	
            </div>			
        </div>
        <div class="pull-right text-right">
            <div class="addBtn DynamicAdd"><span class="icon icon-plus"></span>Add total of 1 locations</div>
            <button type="submit" class="btn btn-primary pd-l-40 pd-r-40">Save</button>
        </div>
    </div>
</form>

@if(isset($modal))
<!-- Modal -->
<div id="onboardView" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">Skip</button>
            </div>
            <div class="modal-body">
                <div class="carousel slide " id="fade-quote-carousel" data-ride="carousel" data-interval="false">
                    <!-- Carousel indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#fade-quote-carousel" data-slide-to="0"></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="1"></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="2" ></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="3" class="active"></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="4"></li>
                    </ol>
                    <!-- Carousel items -->
                    <div class="carousel-inner">
                        <div class="item">

                            <div class="onboard-img" ><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio .</p>
                            </blockquote>	
                        </div>
                        <div class="item">

                            <div class="onboard-img"><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio </p>
                            </blockquote>
                        </div>
                        <div class=" item">

                            <div class="onboard-img"><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                            </blockquote>
                        </div>
                        <div class="active item">

                            <div class="onboard-img"><img src="{{asset('web/images/create_profile.png')}}" alt=""></div>
                            <h3 class="onboard-title">Create Your Profile </h3>
                            <blockquote>
                                <p>Modern medicine has known a rapid progress in the last decades and many traditional forms of treatment have been replaced by new, improved medical…Modern medicine has known a rapid progress in the last decades and many traditional forms.</p>
                            </blockquote>
                        </div>
                        <div class="item">

                            <div class="onboard-img"><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
@section('js')
<script>
    $('.carousel').carousel();
</script>
<script>
    $('.ddlCars').multiselect({
        numberDisplayed: 3,
    });

</script>
@endsection
@endsection