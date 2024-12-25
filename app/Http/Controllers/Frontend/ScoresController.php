<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyScoreRequest;
use App\Http\Requests\StoreScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\Game;
use App\Models\Score;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class ScoresController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('score_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scores = Score::with(['game', 'user'])->get();

        return view('frontend.scores.index', compact('scores'));
    }

    public function create()
    {
        abort_if(Gate::denies('score_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $games = Game::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.scores.create', compact('games', 'users'));
    }

    public function store(Request $request)
    {

    if(Auth::check()) {

        $score = new Score();
        $score->swipe_right = $request->swipedRightCount;
        $score->swipe_left = $request->swipedLeftCount;
        $score->user_id = Auth::user()->id;
       // $score->game_id = $request->game_id;
        $score->save();

        //fetch score for this game
       // $score = Score::where('game_id', $request->game_id)->get();

         // Logic to update the score
         return response()->json([
            'score' => $request->score,
            'swipedRightCount' => $score->swipe_right,
            'swipedLeftCount' => $score->swipe_left,
        ]);


    } else {
        return;
    }
       
      //  $score = Score::create($request->all());
      //  return redirect()->route('frontend.scores.index');
    }

    public function edit(Score $score)
    {
        abort_if(Gate::denies('score_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $games = Game::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $score->load('game', 'user');

        return view('frontend.scores.edit', compact('games', 'score', 'users'));
    }

    public function update(UpdateScoreRequest $request, Score $score)
    {
        $score->update($request->all());

        return redirect()->route('frontend.scores.index');
    }

    public function show(Score $score)
    {
        abort_if(Gate::denies('score_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $score->load('game', 'user');

        return view('frontend.scores.show', compact('score'));
    }

    public function destroy(Score $score)
    {
        abort_if(Gate::denies('score_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $score->delete();

        return back();
    }

    public function massDestroy(MassDestroyScoreRequest $request)
    {
        $scores = Score::find(request('ids'));

        foreach ($scores as $score) {
            $score->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
