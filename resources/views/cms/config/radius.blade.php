@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update Search Radius</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/config/store-radius') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('radius') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Radius</label>

                            <div class="col-md-6">
                                <input type="text"  class="form-control" name="radius" value="{{ $radius->config_data }}">

                                @if ($errors->has('radius'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('radius') }}</strong>
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
