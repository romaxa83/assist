<template>
  <div class="row mb-2">
    <NoteListItem
        v-for="note in notes"
        :key="note.id"
        :note="note"
    />
  </div>
</template>

<script>

import NoteListItem from "./NoteListItem.vue";

export default {
  name: "NoteList",
  components: {
    NoteListItem,
  },
  props: {
    notes: {
      type: Array,
      required: true,
    },
  },
}
</script>

<style scoped>

</style>