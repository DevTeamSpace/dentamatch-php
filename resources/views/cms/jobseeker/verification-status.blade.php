@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Verification Status</div>
          <div class="panel-body">
            <table id="jobseeker_verification" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>License Number</th>
                <th>License State</th>
                <th>Job Title</th>
                <th>Status</th>
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
