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
      waitForPlayer() {
        Echo.join(`game.${this.game}`)
          .joining(player => this.assignSecondPlayer(player))
          .listen('PlayerAssigned', (data) => {
            this.hideLoading()

            this.determineSides(data.game)
          })
          .listen('MoveMade', () => {
            this.getPosition()

            this.isMyMove = ! this.isMyMove
          })
      },

      assignSecondPlayer(player) {
        axios.post('/api/chess/assignSecondPlayer', {player, game: this.game})
      },

      hideLoading() {
        this.loading = false
      },

      determineSides(game) {
        if (game.white === this.user.id) {
          this.isMyMove = true

          return this.side = 'white'
        }

        return this.side = 'black'
      },

      move(to) {
        let from = this.selected
        let game = this.game

        axios.post('/api/chess/move', {game, to, from})
          .then(() => {
            this.selected = null
          })
      },

      getTileKey(file, rank) {
        return `${file}${rank}`
      },

      getPiece(file, rank) {
        return this.pieces[this.getTileKey(file, rank)]
      },

      getPosition() {
        axios.get(`/api/chess/getPosition/${this.game}`)
          .then(response => this.pieces = response.data)
      },

      loadUser() {
        axios.get('/api/user')
          .then(response => this.user = response.data)
      }
    }
  }
</script>
