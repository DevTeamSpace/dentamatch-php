@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Update Skill</div>
          <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/school/store') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('parent_school') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Parent School</label>
                <div class="col-md-6">
                  <select class="form-control" name="parent_school">
                    <option value="">Select</option>
                    <option {{ $school->parent_id == '0' ?'selected':'' }} value="0">Parent School</option>
                    @foreach($parentSchools as $parent)
                      <option {{ $school->parent_id == $parent->id ?'selected':'' }} value="{{ $parent->id }}">
                        {{ $parent->school_name }}
                      </option>
                    @endforeach
                  </select>
                  @if ($errors->has('parent_school'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('parent_school') }}</strong>
                                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('school') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">School Name</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" name="school" value="{{ $school->school_name }}">
                  <input type="hidden" name="id" value="{{ $school->id }}">
                  @if ($errors->has('school'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('school') }}</strong>
                                    </span>
                  @endif
                </div>
              </div>


              <div class="form-group">
                <label class="col-md-4 control-label">Is active</label>

                <div class="col-md-6" style="margin-top: 7px;">
                    <?php $checked = ($school->is_active) ? 'checked' : ''?>
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
