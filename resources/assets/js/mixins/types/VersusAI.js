export default {
  methods: {
    init() {
      Echo.join(`game.${this.id}`)
        .here(() => {
          this.assignAI()
        })
        .listen('AIAssigned', (data) => {
          this.hideLoading()

          this.determineRoles(data.game)

          this.checkMoveOrder(data.game)

          this.attemptAIMove()
        })
        .listen('MoveMade', (data) => {
          this.board.setMoves(data.game.moves)

          this.checkMoveOrder(data.game)

          this.resetRollback()

          this.attemptAIMove();
        })

    },

    assignAI() {
      axios.post(`/api/games/${this.id}/assignAI`)
    },

    AIToMove() {
      return this.isMyMove === false;
    },

    attemptAIMove() {
      if (this.AIToMove()) {
        let move = this.AI.think(this.board);

        this.move(move);
      }
    }
  }
}
