@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Update Location</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/location/store') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Zipcode</label>

                <div class="col-md-6">
                  <input type="number" minlength="5" maxlength="6" class="form-control" name="zipcode"
                         value="{{ $location->zipcode }}">
                  <input type="hidden" name="id" value="{{ $location->id }}">
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
                  <input type="text" maxlength="255" class="form-control" name="description"
                         value="{{ $location->description }}">
                </div>
              </div>

            <!--                        <div class="form-group">
                            <label class="col-md-4 control-label">Free Trial Period</label>
                            <div class="col-md-6">
                                <select class="form-control" name="free_trial_period" >
                                    <option {{ ($location->free_trial_period=='0'?'selected':'') }} value="0">0</option>
                                    <option {{ ($location->free_trial_period=='1'?'selected':'') }} value="1">1</option>
                                    <option {{ ($location->free_trial_period=='2'?'selected':'') }} value="2">2</option>
                                    <option {{ ($location->free_trial_period=='3'?'selected':'') }} value="3">3</option>
                                    <option {{ ($location->free_trial_period=='4'?'selected':'') }} value="4">4</option>
                                    <option {{ ($location->free_trial_period=='5'?'selected':'') }} value="5">5</option>
                                    <option {{ ($location->free_trial_period=='6'?'selected':'') }} value="6">6</option>
                                </select>
                            </div>
                        </div>-->


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
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
