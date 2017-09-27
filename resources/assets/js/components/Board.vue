<template>
  <div class="chessboard">
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

    <div class="loading" v-show="loading">Loading</div>

    <moves
      :list="moves"
      @rollback="(val) => { rollback = val }"
    ></moves>
  </div>
</template>

<script>
  import Moves from './Moves.vue'
  import Position from '../chess/Position'
  import Tile from './Tile.vue'

  export default {
    props: ['game'],

    components: {Tile, Moves},

    data() {
      return {
        files: _.range(8),
        isMyMove: false,
        loading: true,
        moves: [],
        ranks: _.range(8),
        rollback: false,
        selected: null,
        side: '',
        user: {}
      }
    },

    computed: {
      pieces() {
        return Position.calculateFromMoves(this.moves, this.rollback)
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
        Echo.join(`game.${this.game}`)
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
            this.setMoves(data.game.moves)

            this.isMyMove = ! this.isMyMove

            this.rollback = false;
          })
      },

      /**
       * Assigns the second player to the game as black.
       */
      joinRoom(user) {
        axios.post(`/api/chess/${this.game}/joinRoom`, {user})
      },

      /**
       * Hides the loading element after the game starts.
       */
      hideLoading() {
        this.loading = false
      },

      /**
       * Determines which player play which side by user IDs.
       */
      determineRoles(game) {
        if (game.white === this.user.id) {
          return this.side = 'white'
        }

        if (game.black === this.user.id) {
          this.reverseBoard();

          return this.side = 'black'
        }

        this.side = 'spectator'
      },

      /**
       * Reverses the board for the player playing black pieces.
       */
      reverseBoard() {
        this.files = _.reverse(this.files);
        this.ranks = _.reverse(this.ranks);
      },

      /**
       * Checks whether the given player is on his move.
       */
      checkMoveOrder(game) {
        if ((this.side === 'white' && this.numberOfMoves(game) % 2 === 0)
          || (this.side === 'black' && this.numberOfMoves(game) % 2 !== 0)) {
          return this.isMyMove = true
        }

        return this.isMyMove = false
      },

      /**
       * Returns the nubmer of moves in given game.
       */
      numberOfMoves(game) {
        return game.moves ? game.moves.length : 0
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

        axios.post(`/api/chess/${this.game}/moves`, {to, from, piece})
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
        axios.get(`/api/chess/${this.game}/moves`)
          .then(response => this.setMoves(response.data))
      },

      /**
       * Assigns all current moves to a variable.
       */
      setMoves(moves) {
        this.moves = moves
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
