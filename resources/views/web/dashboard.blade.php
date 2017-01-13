@extends('web.layouts.dashboard')

@section('content')


<form data-parsley-validate>
    <div class="customContainer center-block containerBottom">
        <div class="profieBox">
            <h3>Create Profile</h3>
            <div class="commonBox cboxbottom">

                <div class="form-group">
                    <label >Dental Office Name</label>
                    <input type="text" class="form-control"  data-parsley-required data-parsley-required-message="office name required">
                </div>
                <div class="form-group">
                    <label  >Dental Office Description</label>
                    <textarea class="form-control txtHeight"  data-parsley-required data-parsley-required-message="office description required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" ></textarea>
                </div>

            </div>		

            <div class="commonBox cboxbottom masterBox">

                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Dental Office Type</label>

                    <div class="slt">
                        <select class="ddlCars" multiple="multiple" data-parsley-required data-parsley-required-message="office type required">
                            <option value="Accord">Accord</option>
                            <option value="Duster">Duster</option>
                            <option value="Esteem">Esteem</option>
                            <option value="Fiero">Fiero</option>
                            <option value="Lancer">Lancer</option>
                            <option value="Phantom">Phantom</option>
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label>Dental Office Address</label>
                    <input type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="office address required">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits" >
                </div>


                <div class="form-group">
                    <label >Working Hours</label>
                    <div class="row dayBox EveryDayCheck">
                        <div class="col-sm-4">  
                            <p class="ckBox">
                                <input type="checkbox" id="test2"  />
                                <label for="test2" class="ckColor"> Everyday</label>
                            </p>    
                        </div>
                        <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                        <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" ></div>
                    </div>


                    <div class="allDay">  
                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="mon"  />
                                    <label for="mon" class="ckColor"> Monday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" ></div>
                        </div>


                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="tue"  />
                                    <label for="tue" class="ckColor"> Tuesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="wed" />
                                    <label for="wed" class="ckColor"> Wednesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="thu"  />
                                    <label for="thu" class="ckColor"> Thursday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours"data-parsley-required data-parsley-required-message="closing hours required"  ></div>
                        </div>


                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="fri"  />
                                    <label for="fri" class="ckColor"> Friday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" ></div>
                        </div>


                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sat"  />
                                    <label for="sat" class="ckColor"> Saturday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sun" />
                                    <label for="sun" class="ckColor"> Sunday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" ></div>
                        </div>

                    </div>





                </div>	

                <div class="form-group">
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea class="form-control txtHeight"  data-parsley-required data-parsley-required-message="location information required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" ></textarea>
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