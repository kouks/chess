export default {
  methods: {
    /**
     * Join the presence channel and listen for events:
     *  1. Player joined the room event
     *  2. Players have been assigned sides event
     *  3. Player has made a move event
     */
    init() {
      Echo.join(`game.${this.id}`)
        .joining((user) => {
          this.joinRoom(user)
        })
        // .leaving((user) => { TODO
        //   this.leaveRoom(user)
        // })
        .listen('PlayerJoined', (data) => {
          this.hideLoading()

          this.determineRoles(data.game)

          this.checkMoveOrder(data.game)
        })
        .listen('MoveMade', (data) => {
          this.board.setMoves(data.game.moves)

          this.checkMoveOrder(data.game)

          this.resetRollback()
        })
    },

    /**
     * Assigns the second player to the game as black or add a spectator.
     */
    joinRoom(user) {
      axios.post(`/api/games/${this.id}/joinRoom`, {user})
    }
  }
}
