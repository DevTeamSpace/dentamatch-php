@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Area List
          </div>

          <div class="panel-body">
            <table id="activities_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
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
