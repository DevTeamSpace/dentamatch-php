@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Recruiter List
            <div class="span6 pull-right" style="text-align:right">
              <a href="{{ URL::to("cms/recruiter/create") }}">Add Recruiter</a>
            </div>
            <div class="span6 pull-right" style="text-align:right; margin-right: 15px;">
              <a href="{{ url('cms/recruiter/csvRecruiter') }}">Download CSV</a>
            </div>
          </div>

          <div class="panel-body">
            <table id="recruiter_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Email</th>
                <th>Office Name</th>
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
