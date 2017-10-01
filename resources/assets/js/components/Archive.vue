<template>
  <div class="panel panel-default" v-show="games.length">
    <div class="panel-heading">Archive</div>

    <div class="panel-body">
      <ul class="list-group">
        <li class="list-group-item" v-for="game in games">
          <a :href="`/games/${game._id.$oid}`">{{ game._id.$oid }}</a>
          Played as {{ getSide(game) }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        games: [],
        user: {}
      }
    },

    mounted() {
      this.loadUser();

      this.loadArchive();
    },

    methods: {
      loadArchive() {
        axios.get('/api/games')
          .then(response => this.games = response.data)
      },

      getSide(game) {
        return game.white === this.user.id
         ? 'white'
         : 'black'
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
