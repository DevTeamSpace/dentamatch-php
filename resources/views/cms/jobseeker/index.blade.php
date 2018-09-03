@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">JobSeeker List
<!--                    <div class="span6 pull-right" style="text-align:right">
                        <a href="{{ URL::to("cms/jobseeker/create") }}">Add JobSeeker</a>
                    </div>-->
                </div>

                <div class="panel-body">
                    <table id="jobseeker_list" class="display responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th>Email</th>
                                  <th>First Name</th>
                                  <th>Last Name</th>
                                  <th>Job Title</th>
                                  <th>Preferred Job Location</th>
                                  <th>Registered on</th>
                                  <th>Active</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
			</table>
                </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
