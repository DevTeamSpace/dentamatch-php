@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Add Office Type</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/officetype/store') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('officetype') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Office Type</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" name="officetype" value="{{ old('officetype') }}">

                  @if ($errors->has('officetype'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('officetype') }}</strong>
                                    </span>
                  @endif
                </div>
              </div>


              <div class="form-group">
                <label class="col-md-4 control-label">Is active</label>

                <div class="col-md-6" style="margin-top: 7px;">
                  <input type="checkbox" checked="checked" name="is_active">
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
