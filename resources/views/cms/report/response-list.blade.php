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
                    <div class="span6 pull-right" style="text-align:right"><a href="{{ url('cms/report/download/responselist') }}">Download CSV</a></div>
                </div>
                </div>

                <div class="panel-body">
                    <table id="response_list" class="display responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th>Office Name</th>
                                  <th>Job Title</th>
                                  <th>Job Type</th>
                                  <th>Invited</th>
                                  <th>Applied</th>
                                  <th>Shortlisted</th>
                                  <th>Hired</th>
                                  <th>Reject</th>
                                  <th>Cancel</th>
                              </tr>
                          </thead>

			</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
