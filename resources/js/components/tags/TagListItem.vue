<template>
  <span
      class="tag_item"
      :class="{ 'active-tag': isActive }"
  >
    {{tag.name}} ({{tag.attached}})
  </span>
</template>

<script>
export default {
  name: "TagListItem",
  props: {
    tag: {
      type: Object,
      required: true,
    },
    isActive: {
      type: Boolean, // Передаём активность через пропс
      default: false,
    },
  },
}
</script>

<style scoped>
.tag_item {
  display: inline-block;
  cursor: pointer;
  background-color: var(--tag-background-color);
  color: var(--tag-color);
  border: 1px solid var(--tag-border-color);
  border-radius: 10px;
  padding: 0.2em 0.6em;
  transition: all 0.3s ease;
  font-weight: bold;
  margin: 3px 5px;
}
.tag_item:hover {
  background-color: var(--tag-hover-background-color);
  color: var(--tag-hover-color);
  border-color: var(--tag-hover-border-color);
}

.tag_item:hover {
  background-color: var(--tag-hover-background-color);
  color: var(--tag-hover-color);
  border-color: var(--tag-hover-border-color);
}

.tag_item.active-tag {
  background-color: var(--tag-active-background-color);
  color: var(--tag-active-color);
  border-color: var(--tag-active-border-color);
}
</style>