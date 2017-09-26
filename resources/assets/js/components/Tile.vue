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
      toggleTileOrMove() {
        if (! this.isMyMove) {
          return
        }

        return this.selected === null || this.selected === this.id
          ? this.toggleTile()
          : this.move()
      },

      move() {
        this.$emit('move', this.id)
      },

      toggleTile() {
        if (! this.selectingMyPiece()) {
          return
        }

        return this.isSelected === 'selected'
          ? this.$emit('selected', null)
          : this.$emit('selected', this.id)
      },

      selectingMyPiece() {
        return this.piece.startsWith(this.side)
      }
    }
  }
</script>
