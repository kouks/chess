<template>
  <div class="chessboard">
    <div v-for="row in rows" class="file">
      <tile v-for="col in cols" :key="`${col}-${row}`"></tile>
    </div>
  </div>
</template>

<script>
  import Tile from './Tile.vue'

  export default {
    props: ['game'],
    components: {Tile},

    data() {
      return {
        rows: _.range(8),
        cols: _.range(8),
        pieces: {
          '4-0': {
            'king': true,
            'black': true,
          },
          '3-0': {
            'queen': true,
            'black': true,
          },
          '4-7': {
            'king': true,
            'white': true,
          }
        }
      }
    },

    mounted() {
      this.waitForPlayer()
    },

    methods: {
      waitForPlayer() {
        Echo.join(`game.${this.game}`)
          .joining(user => this.startGame(user))
      },

      startGame(user) {

      },

      selectPiece(clicked) {
        let piece = this.pieces[clicked]

        if (piece === undefined) {
          return false
        }

        this.pieces[clicked] = $.extend(piece, {selected: true})
        console.log(piece)
      }
    }
  }
</script>
