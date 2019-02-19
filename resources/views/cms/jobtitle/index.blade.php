@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Job Title List
            <div class="span6 pull-right" style="text-align:right">
              <a href="{{ URL::to("cms/jobtitle/create") }}">Add Job Title</a>
            </div>
          </div>

          <div class="panel-body">
            <table id="jobtitle_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Job Title</th>
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
@endsection
