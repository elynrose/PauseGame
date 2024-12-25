@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.game.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.games.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.game.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $game->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.game.fields.video') }}
                                    </th>
                                    <td>
                                        @if($game->video)
                                            <a href="{{ $game->video->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.game.fields.video_url') }}
                                    </th>
                                    <td>
                                        {{ $game->video_url }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.game.fields.time_stamps') }}
                                    </th>
                                    <td>
                                        {{ $game->time_stamps }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.game.fields.attempts') }}
                                    </th>
                                    <td>
                                        {{ $game->attempts }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.game.fields.active') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $game->active ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.games.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection