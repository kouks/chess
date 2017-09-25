<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('games.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        return view('games.show', compact('game'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new()
    {
        $game = mongo()->games->insertOne([
            'white' => auth()->id(),
        ])->getInsertedId();

        return redirect()->route('games.show', compact('game'));
    }
}
