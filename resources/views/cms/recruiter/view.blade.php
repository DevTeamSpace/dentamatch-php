@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Verification Details</div>
                <div class="panel-body">
                    <div class="form-group">
                    {!! csrf_field() !!}
                    <input type="hidden" name="user_id" value="{{ $userProfile->id }}" />
                    <table class="table table-user-information">
                        <tbody>
                            <tr>
                                <td>Dental Office Name</td>
                                @if(!empty($userProfile->office_name))
                                <td>{{ $userProfile->office_name }}</td>
                                @else
                                <td>N/A</td>
                                @endif
                            </tr>
                            <tr>
                               <td>Dental Office Description</td> 
                                @if(!empty($userProfile->office_desc))
                                <td>{{ $userProfile->office_desc }}</td>
                                @else
                                <td>N/A</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <a href="<?php echo e(url('cms/recruiter/index')); ?>"  class="btn btn-primary">
                                <i class="fa fa-backward"></i> Return to list
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
