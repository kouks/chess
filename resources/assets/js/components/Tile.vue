<template>
  <div :class="['tile', selectedClass]">
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
      selectedClass() {
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

        return this.shouldToggleTile()
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

        return this.selectedClass === 'selected'
          ? this.$emit('selected', null)
          : this.$emit('selected', this.id)
      },

      /**
       * Determines whether to toggle tile by:
       *  1. If a piece is not selected
       *  2. If the selected tile is not the same as requested
       *  3. If I am targetting my own piece
       */
      shouldToggleTile() {
        return this.selected === null || this.selected === this.id || this.selectingMyPiece()
      },

      /**
       * Decides whether the player is selecting his pieces.
       */
      selectingMyPiece() {
        return this.piece && this.piece.startsWith(this.side)
      }
    }
  }
</script>
