<template>
  <div :class="['tile', isSelected]">
    <div
      :class="['piece', piece]"
      @click="toggleTileOrMove()"
    ></div>
  </div>
</template>

<script>
  export default {
    props: ['id', 'piece', 'selected', 'side', 'isMyMove'],

    computed: {
      isSelected() {
        return this.id === this.selected ? 'selected' : ''
      }
    },

    methods: {
      /**
       * Determines whether the player wants to select a piece or move it.
       */
      toggleTileOrMove() {
        if (! this.isMyMove) {
          return
        }

        return this.selected === null || this.selected === this.id
          ? this.toggleTile()
          : this.move()
      },

      /**
       * Sends an event, letting the board know that the player decided to move.
       */
      move() {
        this.$emit('move', this.id)
      },

      /**
       * Toggles selection status for a given tile.
       */
      toggleTile() {
        if (! this.selectingMyPiece()) {
          return
        }

        return this.isSelected === 'selected'
          ? this.$emit('selected', null)
          : this.$emit('selected', this.id)
      },

      /**
       * Decides whether the player is selecting his pieces.
       */
      selectingMyPiece() {
        return this.piece.startsWith(this.side)
      }
    }
  }
</script>
