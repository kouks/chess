<template>
  <div class="move-list" v-show="list.length">
    <div class="move-list-item">
      <div class="move" v-for="move in list">{{ transform(move.to, move.piece) }}</div>
    </div>
  </div>
</template>

<script>
  import Move from '../chess/Move'

  export default {
    props: ['list'],

    computed: {
      couples() {
        let couples = {}
        this.list.map
      }
    },

    methods: {
      transform(tile, piece) {
        return Move.transform(tile, piece)
      }
    }
  }
</script>
