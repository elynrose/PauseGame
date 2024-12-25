<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyGameRequest;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class GamesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('game_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $games = Game::with(['media'])->get();

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        abort_if(Gate::denies('game_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.games.create');
    }

    public function store(StoreGameRequest $request)
    {
        $game = Game::create($request->all());

        if ($request->input('video', false)) {
            $game->addMedia(storage_path('tmp/uploads/' . basename($request->input('video'))))->toMediaCollection('video');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $game->id]);
        }

        return redirect()->route('admin.games.index');
    }

    public function edit(Game $game)
    {
        abort_if(Gate::denies('game_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.games.edit', compact('game'));
    }

    public function update(UpdateGameRequest $request, Game $game)
    {
        $game->update($request->all());

        if ($request->input('video', false)) {
            if (! $game->video || $request->input('video') !== $game->video->file_name) {
                if ($game->video) {
                    $game->video->delete();
                }
                $game->addMedia(storage_path('tmp/uploads/' . basename($request->input('video'))))->toMediaCollection('video');
            }
        } elseif ($game->video) {
            $game->video->delete();
        }

        return redirect()->route('admin.games.index');
    }

    public function show(Game $game)
    {
        abort_if(Gate::denies('game_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.games.show', compact('game'));
    }

    public function destroy(Game $game)
    {
        abort_if(Gate::denies('game_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $game->delete();

        return back();
    }

    public function massDestroy(MassDestroyGameRequest $request)
    {
        $games = Game::find(request('ids'));

        foreach ($games as $game) {
            $game->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('game_create') && Gate::denies('game_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Game();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
