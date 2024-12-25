@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.score.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.scores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.score.fields.game') }}
                        </th>
                        <td>
                            {{ $score->game->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.score.fields.swipe_left') }}
                        </th>
                        <td>
                            {{ $score->swipe_left }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.score.fields.swipe_right') }}
                        </th>
                        <td>
                            {{ $score->swipe_right }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.score.fields.user') }}
                        </th>
                        <td>
                            {{ $score->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.scores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection