@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Notification Message List
            <div class="span6 pull-right" style="text-align:right">
              <a href="{{ URL::to("cms/notify/create") }}">Add Notification Message</a>
            </div>
          </div>

          <div class="panel-body">
            <table id="appMessage_list" class="display responsive table nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Users</th>
                <th>Message</th>
                <th>Notification Status</th>
                <th>Created At</th>
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
