<template>
  <div class="move-list" v-show="list.length">
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
  import Move from '../chess/Move'

  export default {
    props: ['list'],

    methods: {
      /**
       * Returs parsed notation.
       */
      transform(tile, piece) {
        return Move.transform(tile, piece)
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
