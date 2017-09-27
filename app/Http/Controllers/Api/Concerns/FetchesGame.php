<?php

namespace App\Http\Controllers\Api\Concerns;

use MongoDB\BSON\ObjectID;

trait FetchesGame
{
    /**
     * Fetches a game by provided id.
     *
     * @param  string  $gameId
     * @return \MongoDB\Model\BSONDocument
     */
    public function getGameById($gameId)
    {
        return mongo()->games->findOne([
            '_id' => new ObjectID($gameId),
        ]);
    }
}
