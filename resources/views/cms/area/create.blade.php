@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Add Location</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/area/store') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('preferred_location_name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Name</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" name="preferred_location_name"
                         value="{{ old('preferred_location_name') }}">

                  @if ($errors->has('preferred_location_name'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('preferred_location_name') }}</strong>
                                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('anchor_zipcode') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Zipcode</label>

                <div class="col-md-6">
                  <input type="number" minlength="5" maxlength="8" class="form-control" name="anchor_zipcode"
                         value="{{ old('anchor_zipcode') }}">

                  @if ($errors->has('anchor_zipcode'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('anchor_zipcode') }}</strong>
                                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('radius') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Radius, miles</label>

                <div class="col-md-6">
                  <input type="number" minlength="5" maxlength="8" class="form-control" name="radius"
                         value="{{ old('radius') }}">

                  @if ($errors->has('radius'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('radius') }}</strong>
                                    </span>
                  @endif
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

              <div class="bs-callout bs-callout-success">
                <p>Zipcodes for area would be fetched after you click <code>Save</code>.</p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
