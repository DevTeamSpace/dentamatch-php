@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add AppMessage</div>
<!--                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif-->
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/notify/store') }}">
                        {!! csrf_field() !!}
                        
                        <div class="form-group{{ $errors->has('message_to') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Message To</label>

                            <div class="col-md-6">
                                <select class="form-control" name="message_to">
                                    <option value="1">All</option>
                                    <option value="2">Recruiter</option>
                                    <option value="3">Jobseeker</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Message</label>

                            <div class="col-md-6">
                                <textarea class="form-control" rows="7" cols="20" name="message">{{ old('message') }}</textarea>

                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
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
