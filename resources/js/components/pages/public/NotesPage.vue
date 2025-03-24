<template>
  <div class="container">
    <div class="row g-5">
      <div class="col-md-8">
        <div class="search_note">


        </div>
        <div>
          <NoteList
              :notes="notes"
          />
        </div>
      </div>
      <div class="col-md-4">
        <div class="position-sticky">
          <TagList
              :tags="tags || []"
              :selectedTags="selectedTags"
          />
        </div>
      </div>
    </div>

  </div>
</template>

<script>

import { ref, onMounted } from 'vue';
import axios from 'axios';
import NoteList from "../../notes/NoteList.vue";
import TagList from "../../tags/TagList.vue";

export default {
  name: "MainPage",
  components: {
    NoteList,
    TagList
  },
  setup(){
    const notes = ref([]);
    const tags = ref([]);
    const selectedTags = ref([]);

    onMounted(async () => {
      try {
        const response = await axios.get('/api/notes',{
          params: {
            per_page: 20,
          },
        });

        const responseTags = await axios.get('/api/tags');

        console.log(responseTags.data);

        notes.value = response.data.data;
        tags.value = responseTags.data;
      } catch (error) {
        console.error('Ошибка загрузки заметок:', error);
      }
    });

    return {
      notes,
      tags,
      selectedTags,
    };

  }
}
</script>

<style scoped>
.search_note{
  margin-top: 1rem;
  margin-bottom: 1rem;
}
</style>