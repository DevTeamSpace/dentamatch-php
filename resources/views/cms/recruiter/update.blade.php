@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update Recruiter</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/recruiter/store') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{ $userProfile->id }}">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input type="text"  class="form-control" name="email"  value="{{ $userProfile->email }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Is active</label>

                            <div class="col-md-6" style="margin-top: 7px;">
                                <?php $checked = ($userProfile->is_active)?'checked':''?>
                                <input type="checkbox" name="is_active" <?=$checked?> >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-save"></i>Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
