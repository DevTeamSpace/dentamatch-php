@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Update Affiliation</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST"
                  action="{{ url('cms/affiliation/store') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('affiliation') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Affiliation</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" name="affiliation"
                         value="{{ $affiliation->affiliation_name }}">
                  <input type="hidden" name="id" value="{{ $affiliation->id }}">
                  @if ($errors->has('affiliation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('affiliation') }}</strong>
                                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">Is active</label>

                <div class="col-md-6" style="margin-top: 7px;">
                    <?php $checked = ($affiliation->is_active) ? 'checked' : ''?>
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
