<?php
/** @var \App\Models\PreferredJobLocation $location */
?>

@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Update Location</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/area/store') }}">
              {!! csrf_field() !!}

              <input type="hidden" name="id" value="{{ $location->id }}">

              <div class="form-group{{ $errors->has('preferred_location_name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Name</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" name="preferred_location_name"
                         value="{{ $location->preferred_location_name }}">

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
                  <input type="number" minlength="5" maxlength="6" class="form-control" name="anchor_zipcode"
                         value="{{ $location->anchor_zipcode }}">
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
                  <input type="number" minlength="5" maxlength="6" class="form-control" name="radius"
                         value="{{ $location->radius }}">
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
                    <?php $checked = ($location->is_active) ? 'checked' : ''?>
                  <input type="checkbox" name="is_active" <?=$checked?> >
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn fa-edit"></i>Update
                  </button>
                </div>
              </div>

              <div class="bs-callout bs-callout-success">
                <p>Zip codes for the area would be updated if you change <code>Zipcode</code> or <code>Radius</code>.</p>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
