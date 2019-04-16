@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Area List
            <div class="span6 pull-right" style="text-align:right">
              <a href="{{ URL::to("cms/area/create") }}">Add Area</a>
            </div>
          </div>

          <div class="panel-body">
            <script id="delete-record-message" type="text">
              Are you sure you want to delete this record? <br> <br>
              <strong>All zip codes associated with this area would be deleted too</strong>
            </script>
            <table id="area_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Name</th>
                <th>Zipcode</th>
                <th>Radius</th>
                <th>Zip codes</th>
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
