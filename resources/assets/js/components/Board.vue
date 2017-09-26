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
        @selected="(val) => { selected = val }"
        @move="(val) => { move(val) }"
      ></tile>
    </div>

    <div class="loading" v-show="loading">Loading</div>
  </div>
</template>

<script>
  import Tile from './Tile.vue'

  export default {
    props: ['game'],

    components: {Tile},

    data() {
      return {
        files: _.range(8),
        loading: true,
        isMyMove: false,
        pieces: {},
        ranks: _.range(8),
        selected: null,
        side: '',
        user: {}
      }
    },

    mounted() {
      this.loadUser()

      this.waitForPlayer()

      this.getPosition()
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
          .joining((player) => {
            this.assignSecondPlayer(player)
          })
          .listen('PlayerAssigned', (data) => {
            this.hideLoading()

            this.determineSides(data.game)
          })
          .listen('MoveMade', () => {
            this.getPosition()

            this.isMyMove = ! this.isMyMove
          })
      },

      /**
       * Assigns the second player to the game as black.
       */
      assignSecondPlayer(player) {
        axios.post('/api/chess/assignSecondPlayer', {player, game: this.game})
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
      determineSides(game) {
        if (game.white === this.user.id) {
          this.isMyMove = true

          return this.side = 'white'
        }

        return this.side = 'black'
      },

      /**
       * Makes a move.
       */
      move(to) {
        let from = this.selected
        let game = this.game

        axios.post('/api/chess/move', {game, to, from})
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
       * Assigns the current position to the pieces variable.
       */
      getPosition() {
        axios.get(`/api/chess/getPosition/${this.game}`)
          .then(response => this.pieces = response.data)
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
