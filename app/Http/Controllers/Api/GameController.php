<?php

namespace App\Http\Controllers\Api;

use MongoDB\BSON\ObjectID;
use App\Events\PlayerJoined;
use Illuminate\Http\Request;
use MongoDB\Model\BSONDocument;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FetchesGame;

class GameController extends Controller
{
    use FetchesGame;

    /**
     * Display a listing of the resource.
     *
     * @param  string  $gameId
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = mongo()->games->find([
            '$or' => [
                ['white' => auth()->id()],
                ['black' => auth()->id()],
            ],
        ])->toArray();

        return response($games, 200);
    }

    /**
     * Assings a second player to the game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $gameId
     * @return \Illuminate\Http\Response
     */
    public function joinRoom(Request $request, $gameId)
    {
        $game = $this->getGameById($gameId);

        if (! $this->alreadyInRoom($request->user['id'], $game)) {
            mongo()->games->updateOne([
                '_id' => new ObjectID($gameId),
            ], $this->buildAmendment($game, $request));
        }

        event(new PlayerJoined($this->getGameById($gameId)));

        return response('Player joined.', 202);
    }

    /**
     * Builds the update query for a game.
     *
     * @param  \MongoDB\Model\BSONDocument  $game
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function buildAmendment(BSONDocument $game, Request $request)
    {
        if ($game['black'] !== false) {
            return [
                '$push' => ['spectators' => $request->user['id'] ],
            ];
        }

        return [
            '$set' => ['black' => $request->user['id'] ],
        ];
    }

    /**
     * Determines whether a given user is already in room.
     *
     * @param  int  $id
     * @param  \MongoDB\Model\BSONDocument  $game
     * @return bool
     */
    public function alreadyInRoom($id, BSONDocument $game)
    {
        return in_array($id, [$game['white'], $game['black']]) || in_array($id, (array) $game['spectators']);
    }
}
