@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.score.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.scores.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="game_id">{{ trans('cruds.score.fields.game') }}</label>
                            <select class="form-control select2" name="game_id" id="game_id" required>
                                @foreach($games as $id => $entry)
                                    <option value="{{ $id }}" {{ old('game_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('game'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('game') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.score.fields.game_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="swipe_left">{{ trans('cruds.score.fields.swipe_left') }}</label>
                            <input class="form-control" type="number" name="swipe_left" id="swipe_left" value="{{ old('swipe_left', '0') }}" step="1">
                            @if($errors->has('swipe_left'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('swipe_left') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.score.fields.swipe_left_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="swipe_right">{{ trans('cruds.score.fields.swipe_right') }}</label>
                            <input class="form-control" type="number" name="swipe_right" id="swipe_right" value="{{ old('swipe_right', '0') }}" step="1">
                            @if($errors->has('swipe_right'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('swipe_right') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.score.fields.swipe_right_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.score.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.score.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection