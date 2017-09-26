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
      transform(tile, piece) {
        return Move.transform(tile, piece)
      },

      rollback(move) {
        this.$emit('rollback', move)
      }
    }
  }
</script>
