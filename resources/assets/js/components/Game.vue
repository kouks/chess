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
              @move="(val) => { validateAndBuildMove(val) }"
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
          @rollback="(val) => { rollback = val; selected = null }"
        ></moves>
      </div>
    </div>
  </div>
</template>

<script>
  import Core from '../ai/Core'
  import Moves from './Moves.vue'
  import Board from '../chess/Board'
  import Tile from './Tile.vue'
  import ToMove from './ToMove.vue'
  import VersusPlayer from '../mixins/types/VersusPlayer.js'
  import VersusAI from '../mixins/types/VersusAI.js'

  export default {
    props: ['id'],

    components: {Tile, Moves, ToMove},

    mixins: [VersusAI],

    data() {
      return {
        AI: new Core,
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

      this.init()

      this.loadMoves()
    },

    methods: {
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
       * Builds up the move query.
       */
      validateAndBuildMove(to) {
        let from = this.selected

        // need to determine if it would be a taking move/check/checkmate
        // let position = this.pieces
        // if (! Move.valid(from, to, position)) {
        //   return
        // }

        this.move({to, from});
      },

      /**
       * Validates a move and eventually makes it.
       */
      move(data) {
        let piece = this.pieces[data.from]

        axios.post(`/api/chess/${this.id}/moves`, $.extend(data, {piece}))
          .then(() => {
            this.selected = null
          })
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
