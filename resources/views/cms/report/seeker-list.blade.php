@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table  class="display responsive nowrap" cellspacing="0" >
                              <tr>
                                  <td>Job Title : </td>
                                  <td align="left">{{ $jobDetail->jobtitle_name}}</td>
                              </tr>
                              @if ($jobDetail->job_type == 3)
                              <tr>
                                  <td>No of Openings :</td>
                                  <td>&nbsp;{{ $jobDetail->no_of_jobs }}</td>
                              </tr>
                              @endif
                    </table>
                    <input type="hidden" name="jobId" id="jobId" value="{{ $jobDetail->id}}" />
                </div>

                <div class="panel-body">
                    <table id="seeker_list" class="display responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th>First Name</th>
                                  <th>Last Name</th>
                                  <th>Job Status</th>
                                  
                              </tr>
                          </thead>

			</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
