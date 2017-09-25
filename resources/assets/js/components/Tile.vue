<template>
  <div class="tile">
  </div>
</template>

<script>
  export default {
    props: []
  }
</script>
<!--
<div v-for="row in rows" class="file">
  <div class="tile"
    v-for="col in cols"
    @click="selectPiece(`${col}-${row}`)"
    :id="`${col}-${row}`"
    v-bind:class="pieces[`${col}-${row}`]"></div>
</div>-->
