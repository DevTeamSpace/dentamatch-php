@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Invited Candidate List
            <div class="span6 pull-right" style="text-align:right">
              <a href="{{ url('cms/jobseeker/downloadInvitedUsers') }}">Download CSV</a>
            </div>
          </div>

          <div class="panel-body">
            <table id="jobseeker_invited_users_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Job Title</th>
                <th>Preferred Job Location</th>
                <th>License</th>
                <th>State</th>
                <th>Registered On</th>
              </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
