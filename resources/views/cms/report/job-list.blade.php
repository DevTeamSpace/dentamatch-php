@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Job List
                    
                </div>

                <div class="panel-body">
                    <table id="job_list" class="display responsive wrap" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th>Office Name</th>
                                  <th>Office Location</th>
                                  <th>Job Title</th>
                                  <th>Job Type</th>
                                  <th>Hourly Wage Offered</th>
                                  <th>Action</th>
                              </tr>
                          </thead>

			</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
