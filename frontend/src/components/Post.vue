<template>
  <div class="max-w-4xl px-10 py-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center">
      <span class="font-light text-gray-600">{{ formattedDate }}</span>
      <div class="flex items-center ml-auto">
        <button
          @click="likePost"
          class="px-2 py-1 bg-gray-600 text-white font-bold rounded hover:bg-gray-500 flex items-center"
        >
          <i class="fas fa-thumbs-up mr-1"></i> {{ data.likes }}
        </button>
        <button
          @click="dislikePost"
          class="px-2 py-1 ml-2 bg-gray-600 text-white font-bold rounded hover:bg-gray-500 flex items-center"
        >
          <i class="fas fa-thumbs-down mr-1"></i> {{ data.dislikes }}
        </button>
      </div>
    </div>
    <div class="mt-2">
      <p class="text-2xl text-gray-700 font-bold hover:underline">
        <router-link :to="{ name: 'PostDetail', params: { post_id: data.post_id } }">
          {{ data.title }}
        </router-link>
      </p>
      <p class="mt-2 text-gray-600">{{ data.content.substring(0, 200) }}...</p>
    </div>
    <div class="flex justify-between items-center mt-4">
      <router-link
        :to="{ name: 'PostDetail', params: { post_id: data.post_id } }"
        class="text-blue-500 hover:underline mt-2 inline-block"
      >
        Read More
      </router-link>
      <div>
        <div class="flex items-center">
          <h1 class="text-gray-700 font-bold hover:underline">Author : {{ data.user.name }}</h1>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    data: Object,
  },
  methods: {
    async likePost() {
      try {
        const response = await axios.post(`http://localhost:8000/api/posts/${this.data.post_id}/like`);
        this.$emit('post-updated', response.data); // Emit updated post, Programmer: Cherry Romandiaz
        this.data.likes++;
      } catch (error) {
        console.error('Failed to like post:', error);
      }
    },
    async dislikePost() {
      try {
        const response = await axios.post(`http://localhost:8000/api/posts/${this.data.post_id}/dislike`);
        this.$emit('post-updated', response.data); // Emit updated post
        this.data.dislikes++;
      } catch (error) {
        console.error('Failed to dislike post:', error);
      }
    },
  },
  computed: {
    formattedDate() {
      const date = new Date(this.data.updated_at);
      const optionsDate = { day: '2-digit', month: '2-digit', year: 'numeric' };
      const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
      const formattedDate = new Intl.DateTimeFormat('en-GB', optionsDate).format(date);
      const formattedTime = new Intl.DateTimeFormat('en-GB', optionsTime).format(date);
      return `${formattedDate}, ${formattedTime}`;
    }
  }
}
</script>
