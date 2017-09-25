<template>
  <div>
    {{ game }}
  </div>
</template>

<script>
  export default {
    props: ['game'],

    mounted() {
      this.waitForPlayer()
    },

    methods: {
      waitForPlayer() {
        Echo.join(`game.${this.game}`)
          .joining(user => this.startGame(user))
      },

      startGame(user) {
        console.log(user.name);
      }
    }
  }
</script>
