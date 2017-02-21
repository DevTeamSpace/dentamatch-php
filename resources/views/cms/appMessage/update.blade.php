@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit AppMessage</div>
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
                                    
                                    <option <?=(($appMessage->message_to=='1')?'selected':'')?> value="1">All</option>
                                    <option <?=(($appMessage->message_to=='2')?'selected':'')?> value="2">Recruiter</option>
                                    <option <?=(($appMessage->message_to=='3')?'selected':'')?> value="3">Jobseeker</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <textarea class="form-control" rows="7" cols="20" class="form-control" name="message">{{ $appMessage->message }}</textarea>
                                <input type="hidden" name="id" value="{{ $appMessage->id }}">
                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Message Sent</label>

                            <div class="col-md-6">
                                <?php $checked = ($appMessage->message_sent)?'checked':''?>
                                <input type="checkbox" class="form-control" name="message_sent" <?=$checked?> >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-edit"></i>Update
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
