<?php

namespace App\Http\Controllers\Api;

use App\Events\MoveMade;
use MongoDB\BSON\ObjectID;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MovesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $gameId
     * @return \Illuminate\Http\Response
     */
    public function index($gameId)
    {
        $game = mongo()->games->findOne([
            '_id' => new ObjectID($gameId),
        ]);

        if (! isset($game['moves'])) {
            return response([], 200);
        }

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
                'moves' => [
                    'from' => $request->from,
                    'to' => $request->to,
                ]
            ],
        ]);

        $game = mongo()->games->findOne([
            '_id' => new ObjectID($gameId),
        ]);

        event(new MoveMade($game));

        return response('Move made.', 202);
    }
}
