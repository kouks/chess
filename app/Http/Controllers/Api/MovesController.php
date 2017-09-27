<?php

namespace App\Http\Controllers\Api;

use App\Events\MoveMade;
use MongoDB\BSON\ObjectID;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FetchesGame;

class MovesController extends Controller
{
    use FetchesGame;

    /**
     * Display a listing of the resource.
     *
     * @param  string  $gameId
     * @return \Illuminate\Http\Response
     */
    public function index($gameId)
    {
        $game = $this->getGameById($gameId);

        return response($game['moves'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $gameId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $gameId)
    {
        mongo()->games->updateOne([
            '_id' => new ObjectID($gameId),
        ], [
            '$push' => [
                'moves' => $request->only('from', 'to', 'piece'),
            ],
        ]);

        $game = $this->getGameById($gameId);

        event(new MoveMade($game));

        return response('Move made.', 202);
    }
}
