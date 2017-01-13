@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add Location</div>
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
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/location/store') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Zipcode</label>

                            <div class="col-md-6">
                                <input type="number" minlength="5" maxlength="6" class="form-control" name="zipcode" value="{{ old('zipcode') }}">

                                @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Description</label>
                            <div class="col-md-6">
                                <input type="text" maxlength="255" class="form-control" name="description" value="{{ old('description') }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Free Trial Period</label>
                            <div class="col-md-6">
                                <select class="form-control" name="free_trial_period" >
                                    <option {{ (old('free_trial_period')=='0'?'selected':'') }} value="0">0</option>
                                    <option {{ (old('free_trial_period')=='0'?'selected':'') }} value="1">1</option>
                                    <option {{ (old('free_trial_period')=='0'?'selected':'') }} value="2">2</option>
                                    <option {{ (old('free_trial_period')=='0'?'selected':'') }} value="3">3</option>
                                    <option {{ (old('free_trial_period')=='0'?'selected':'') }} value="4">4</option>
                                    <option {{ (old('free_trial_period')=='0'?'selected':'') }} value="5">5</option>
                                    <option {{ (old('free_trial_period')=='0'?'selected':'') }} value="6">6</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Is active</label>

                            <div class="col-md-6" style="margin-top: 7px;">
                                <input type="checkbox" name="is_active">
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
