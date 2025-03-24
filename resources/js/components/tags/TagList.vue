<template>
  <div class="p-4">
    <h4 class="fst-italic">Tags</h4>

    <TagListItem
        v-for="tag in tags"
        :key="tag.id"
        :tag="tag"
        :is-active="selectedTags.includes(tag)"
    />
  </div>
</template>

<script>

import TagListItem from "./TagListItem.vue";

export default {
  name: "TagList",
  components: {
    TagListItem,
  },
  props: {
    tags: {
      type: Array,
      required: true,
    },
    selectedTags: {
      type: Array,
      required: true,
    },
  },

  setup(props, { emit }) {

  },
};
</script>

<style scoped>

</style>
