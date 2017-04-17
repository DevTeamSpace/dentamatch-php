@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Verification Details</div>
                <div class="panel-body">
                    <div class="form-group">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/jobseeker/storeVerification') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="user_id" value="{{ $userProfile->id }}" />
                    <table class="table table-user-information">
                        <tbody>
                            <tr>
                                <td>User Id</td>
                                <td>{{ $userProfile->id }}</td>
                            </tr>
                            <tr>
                                <td>First Name</td>
                                <td>{{ $userProfile->first_name }}</td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td>{{ $userProfile->last_name }}</td>
                            </tr>
                            
                            @if(!empty($userProfile->license_number))
                            <tr>
                                <td>License Number</td>
                                <td>{{ $userProfile->license_number }}</td>
                            </tr>
                            @else
                            <tr>
                                <td>License Number</td>
                                <td>N/A</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            @if( !empty($userProfile->license_number))
                                @if($userProfile->is_job_seeker_verified == 0)
                                <input type="submit" name="verify"  value="Approve" class="btn btn-primary" />
                                <input type="submit" name="verify"  value="Reject" class="btn btn-primary" />
                                @elseif($userProfile->is_job_seeker_verified == 1)
                                <input type="submit" name="verify"  value="Reject" class="btn btn-primary" />
                                @else 
                                <input type="submit" name="verify"  value="Approve" class="btn btn-primary" />
                                @endif
                            @endif
                            
                            <a href="<?php echo e(url('cms/jobseeker/index')); ?>"  class="btn btn-primary">
                                <i class="fa fa-backward"></i> Return to list
                            </a>
                        </div>
                    </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
