@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Location List
          </div>

          <div class="panel-body">
            <table id="location_list" class="display responsive nowrap" cellspacing="0" width="100%" data-area-id="{{ $areaId }}">
              <thead>
              <tr>
                <th>Zipcode</th>
                <th>City</th>
                <th>County</th>
                <th>State</th>
                <th>Active</th>
              </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
