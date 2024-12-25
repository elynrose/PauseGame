@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.game.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.games.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.game.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.game.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="video">{{ trans('cruds.game.fields.video') }}</label>
                <div class="needsclick dropzone {{ $errors->has('video') ? 'is-invalid' : '' }}" id="video-dropzone">
                </div>
                @if($errors->has('video'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.game.fields.video_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="video_url">{{ trans('cruds.game.fields.video_url') }}</label>
                <input class="form-control {{ $errors->has('video_url') ? 'is-invalid' : '' }}" type="text" name="video_url" id="video_url" value="{{ old('video_url', '') }}">
                @if($errors->has('video_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.game.fields.video_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_stamps">{{ trans('cruds.game.fields.time_stamps') }}</label>
                <input class="form-control {{ $errors->has('time_stamps') ? 'is-invalid' : '' }}" type="text" name="time_stamps" id="time_stamps" value="{{ old('time_stamps', '') }}" required>
                @if($errors->has('time_stamps'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_stamps') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.game.fields.time_stamps_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attempts">{{ trans('cruds.game.fields.attempts') }}</label>
                <input class="form-control {{ $errors->has('attempts') ? 'is-invalid' : '' }}" type="number" name="attempts" id="attempts" value="{{ old('attempts', '') }}" step="1">
                @if($errors->has('attempts'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attempts') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.game.fields.attempts_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.game.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.game.fields.active_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    Dropzone.options.videoDropzone = {
    url: '{{ route('admin.games.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="video"]').remove()
      $('form').append('<input type="hidden" name="video" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="video"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($game) && $game->video)
      var file = {!! json_encode($game->video) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="video" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection