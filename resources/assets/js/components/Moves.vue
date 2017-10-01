<template>
  <div class="box move-list" v-show="list.length">
    <div class="move-list-item">
      <div
        class="move"
        v-for="move in list"
        @click="rollback(move)"
      >
        {{ transform(move.to, move.piece) }}
      </div>
    </div>
  </div>
</template>

<script>
  import Transformer from '../chess/Transformer'

  export default {
    props: ['list'],

    methods: {
      /**
       * Returs parsed notation.
       */
      transform(tile, piece) {
        return Transformer.parseMove(tile, piece)
      },

      /**
       * Rollbacks the board in time.
       */
      rollback(move) {
        return this.lastMove() === move
          ? this.$emit('rollback', false)
          : this.$emit('rollback', move)
      },

      /**
       * Determines the last move.
       */
      lastMove() {
        return this.list[this.list.length - 1]
      }
    }
  }
</script>
