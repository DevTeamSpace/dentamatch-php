@extends('layouts.app')

@section('content')
  <div class="container">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Update Pay-rate file
            @if ($payrateUrl!='')
              <div class="span6 pull-right" style="text-align:right">
                <a href="{{ $payrateUrl }}" target="_blank">View Existing Payrate</a>
              </div>
            @endif
          </div>
          <div class="panel-body">
            <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST"
                  action="{{ url('cms/config/store-pay-rate') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('payrate') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Upload Payrate</label>

                <div class="col-md-6 no-border">
                  <input type="file" name="payrate"
                         accept="application/pdf, image/gif, image/jpeg, image/png, image/jpg"/>

                  @if ($errors->has('payrate'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('payrate') }}</strong>
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
