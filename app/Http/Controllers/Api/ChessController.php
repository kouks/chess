<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
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
     * @param  string  $gameId
     * @return \Illuminate\Http\Response
     */
    public function assignSecondPlayer(Request $request, $gameId)
    {
        $game = mongo()->games->findOne([
            '_id' => new ObjectID($gameId),
        ]);

        event(new PlayerAssigned($game));

        if (isset($game['black'])) {
            return response('Player already assigned', 200);
        }

        mongo()->games->updateOne([
            '_id' => new ObjectID($gameId),
        ], [
            '$set' => ['black' => $request->player['id'] ],
        ]);

        $game = mongo()->games->findOne([
            '_id' => new ObjectID($gameId),
        ]);

        return response('Player assigned.', 202);
    }
}
