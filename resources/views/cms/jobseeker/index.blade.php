@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-12 col-md-offset-0">
        <div class="panel panel-default">
          <div class="panel-heading">Filter JobSeeker List</div>
          <div class="panel-body">
            <form method="get">
              <table class="display responsive nowrap" cellspacing="0" width="50%">
                <tr>
                  <td>From :</td>
                  <td><input type="text" id="startDate" name="startDate" class="datepicker"></td>
                  <td>To :</td>
                  <td><input type="text" id="endDate" name="endDate" class="datepicker"></td>
                  <td><input type="button" id="search" name="serach" class="subBtn green" value="Search"></td>
                </tr>
              </table>
            </form>

          </div>
        </div>
      </div>
      <div class="col-md-12 col-md-offset-0">
        <div class="panel panel-default">
          <div class="panel-heading">JobSeeker List
            <div class="span6 pull-right" style="text-align:right">
              <a href="{{ url('cms/jobseeker/csvJobseeker') }}">Download CSV</a>
            </div>
          </div>

          <div class="panel-body">
            <table id="jobseeker_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Job Title</th>
                <th>Preferred Job Location</th>
                <th>Registered on</th>
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
  </div>
@endsection

@section('innerViewJs')
  <script type="text/javascript">
    $(function () {
      $('.datepicker').datetimepicker({
        format: 'DD-M-YYYY HH:mm:00'
      });
    });
  </script>
@endsection
