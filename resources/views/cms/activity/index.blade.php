@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Activity List
            <div class="span6 pull-right" style="text-align:right">
              <a href="{{ URL::to("cms/activity/csv") }}">Download CSV</a>
            </div>
          </div>

          <div class="panel-body">
            <table id="activities_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Type</th>
                <th>User</th>
                <th>Job Title</th>
                <th>Data</th>
              </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
