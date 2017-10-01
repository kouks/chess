<template>
  <div :class="['box', 'mb-1', 'to-move', moveClass]" v-show="! loading">
    <span>{{ toMove }}</span>
  </div>
</template>

<script>
  export default {
    props: ['side', 'isMyMove', 'loading'],

    computed: {
      moveClass() {
        return `${this.determineColor()}-to-move`;
      },

      toMove() {
        return `${this.determineColor().capitalize()} to Move`;
      }
    },

    methods: {
      determineColor() {
        if ((this.side === 'white' && this.isMyMove)
          || (this.side === 'black' && ! this.isMyMove)) {
          return 'white'
        }

        return 'black'
      }
    }
  }
</script>
