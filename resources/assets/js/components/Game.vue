<template>
  <div class="container">
    <div class="row">
      <div class="col-md-9 mb-2">
        <div :class="['chessboard', reversedClass]">
          <div v-for="rank in ranks" class="rank">
            <tile
              v-for="file in files"
              :key="getTileKey(file, rank)"
              :id="getTileKey(file, rank)"
              :piece="getPiece(file, rank)"
              :side="side"
              :isMyMove="isMyMove"
              :selected="selected"
              :rollback="rollback"
              @selected="(val) => { selected = val }"
              @move="(val) => { move(val) }"
            ></tile>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <to-move
          :side="side"
          :isMyMove="isMyMove"
          :loading="loading"
        ></to-move>

        <moves
          :list="board.getMoves()"
          @rollback="(val) => { rollback = val }"
        ></moves>
      </div>
    </div>
  </div>
</template>

<script>
  import Moves from './Moves.vue'
  import Board from '../chess/Board'
  import Tile from './Tile.vue'
  import ToMove from './ToMove.vue'

  export default {
    props: ['id'],

    components: {Tile, Moves, ToMove},

    data() {
      return {
        board: new Board,
        files: _.range(8),
        isMyMove: false,
        loading: true,
        ranks: _.range(8),
        reversedClass: '',
        rollback: false,
        selected: null,
        side: '',
        user: {}
      }
    },

    computed: {
      pieces() {
        return this.board.getPieces(this.rollback)
      }
    },

    mounted() {
      this.loadUser()

      this.waitForPlayer()

      this.loadMoves()
    },

    methods: {
      /**
       * Join the presence channel and listen for events:
       *  1. Player joined the room event
       *  2. Players have been assigned sides event
       *  3. Player has made a move event
       */
      waitForPlayer() {
        Echo.join(`game.${this.id}`)
          .joining((user) => {
            this.joinRoom(user)
          })
          // .leaving((user) => { TODO
          //   this.leaveRoom(user)
          // })
          .listen('PlayerJoined', (data) => {
            this.hideLoading()

            this.determineRoles(data.game)

            this.checkMoveOrder(data.game)
          })
          .listen('MoveMade', (data) => {
            this.board.setMoves(data.game.moves)

            this.checkMoveOrder(data.game)

            this.resetRollback()
          })
      },

      /**
       * Assigns the second player to the game as black or add a spectator.
       */
      joinRoom(user) {
        axios.post(`/api/games/${this.id}/joinRoom`, {user})
      },

      /**
       * Hides the loading element after the game starts.
       */
      hideLoading() {
        this.loading = false
      },

      /**
       * Resets the board to current position.
       */
      resetRollback() {
        this.rollback = false
      },

      /**
       * Determines which player play which side by user IDs.
       */
      determineRoles(game) {
        if (game.white === this.user.id) {
          return this.side = 'white'
        }

        if (game.black === this.user.id) {
          this.reverseBoard()

          return this.side = 'black'
        }

        this.side = 'spectator'
      },

      /**
       * Reverses the board for the player playing black pieces.
       */
      reverseBoard() {
        this.reversedClass = 'reversed'
      },

      /**
       * Checks whether the given player is on his move.
       */
      checkMoveOrder(game) {
        if ((this.side === 'white' && this.numberOfMovesEven(game))
          || (this.side === 'black' && ! this.numberOfMovesEven(game))) {
          return this.isMyMove = true
        }

        return this.isMyMove = false
      },

      /**
       * Checks if the number of moves in game is even.
       */
      numberOfMovesEven(game) {
        return (game.moves ? game.moves.length : 0) % 2 === 0
      },

      /**
       * Validates a move and eventually makes it.
       */
      move(to) {
        let from = this.selected
        let position = this.pieces
        let piece = this.pieces[from]

        // need to determine if it would be a taking move/check/checkmate
        // if (! Move.valid(from, to, position)) {
        //   return
        // }

        axios.post(`/api/chess/${this.id}/moves`, {to, from, piece})
          .then(() => {
            this.selected = null
          })
      },

      /**
       * Builds up the tile key from file and rank variables.
       */
      getTileKey(file, rank) {
        return `${file}${rank}`
      },

      /**
       * Determines if there is a piece standing on given file and rank.
       */
      getPiece(file, rank) {
        return this.pieces[this.getTileKey(file, rank)]
      },

      /**
       * Loads moves from storage.
       */
      loadMoves() {
        axios.get(`/api/chess/${this.id}/moves`)
          .then((response) => {
            this.board.setMoves(response.data)
          })
      },

      /**
       * Loads the logged used from the storage.
       */
      loadUser() {
        axios.get('/api/user')
          .then(response => this.user = response.data)
      }
    }
  }
</script>
