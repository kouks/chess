<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Chess\Position;
use App\Events\MoveMade;
use MongoDB\BSON\ObjectID;
use Illuminate\Http\Request;
use App\Events\PlayerAssigned;
use App\Http\Controllers\Controller;

class ChessController extends Controller
{
    /**
     * Assings a second player to the game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignSecondPlayer(Request $request)
    {
        mongo()->games->updateOne([
            '_id' => new ObjectID($request->game),
        ], [
            '$set' => ['black' => $request->player['id'] ],
        ]);

        $game = mongo()->games->findOne([
            '_id' => new ObjectID($request->game),
        ]);

        event(new PlayerAssigned($game));

        return response('Player assigned.', 202);
    }

    /**
     * Returns the current position for a given game.
     *
     * @param  \App\Chess\Position  $position
     * @param  string  $game
     * @return \Illuminate\Http\Response
     */
    public function getPosition(Position $position, $game)
    {
        $game = mongo()->games->findOne([
            '_id' => new ObjectID($game),
        ]);

        if (! isset($game['moves'])) {
            return response($position->getDefaultPosition(), 200);
        }

        return response($position->calculateFromMoves($game['moves']), 200);
    }

    /**
     * Returns the current position for a given game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request)
    {
        mongo()->games->updateOne([
            '_id' => new ObjectID($request->game),
        ], [
            '$push' => ['moves' => [
                'from' => $request->from,
                'to' => $request->to,
            ]],
        ]);

        $game = mongo()->games->findOne([
            '_id' => new ObjectID($request->game),
        ]);

        event(new MoveMade($game));

        return response('Move made.', 202);
    }
}
