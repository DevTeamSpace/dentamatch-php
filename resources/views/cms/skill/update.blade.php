@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update Skill</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('cms/skill/store') }}">
                        {!! csrf_field() !!}
                        
                        <div class="form-group{{ $errors->has('parent_skill') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Parent Skill</label>
                            <div class="col-md-6">
                                <select class="form-control" name="parent_skill" >
                                    <option  value="">Select</option>
                                    <option {{ $skill->parent_id == '0' ?'selected':'' }} value="0">Parent Skill</option>
                                    @foreach($parentskill as $skills)
                                    <option {{ $skill->parent_id == $skills->id ?'selected':'' }} value="{{ $skills->id }}" > 
                                        {{ $skills->skill_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('parent_skill'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('parent_skill') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('skill') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Skill Name</label>

                            <div class="col-md-6">
                                <input type="text"  class="form-control" name="skill" value="{{ $skill->skill_name }}">
                                <input type="hidden" name="id" value="{{ $skill->id }}">
                                @if ($errors->has('skill'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('skill') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        

                        <div class="form-group">
                            <label class="col-md-4 control-label">Is active</label>

                            <div class="col-md-6" style="margin-top: 7px;">
                                <?php $checked = ($skill->is_active)?'checked':''?>
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
