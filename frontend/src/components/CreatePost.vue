<template>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold text-gray-700 mb-4">Create New Post</h2>
    <form @submit.prevent="createPost">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
        <input
          type="text"
          v-model="title"
          id="title"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="content">Content</label>
        <textarea
          v-model="content"
          id="content"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          rows="6"
          required
        ></textarea>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
        <select
          v-model="status"
          id="status"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        >
          <option value="draft">Draft</option>
          <option value="published">Published</option>
        </select>
      </div>
      <div class="flex items-center justify-between">
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        >
          Publish
        </button>
      </div>
      <div v-if="error" class="text-red-500 mt-4">{{ error }}</div>
      <div v-if="success" class="text-green-500 mt-4">{{ success }}</div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CreatePost',
  data() {
    return {
      title: '',
      content: '',
      status: 'draft', // Default value, Programmer: Cherry Romandiaz
      error: null,
      success: null
    };
  },
  methods: {
    async createPost() {
      const token = localStorage.getItem('token');
      try {
        await axios.post('http://localhost:8000/api/posts', {
          title: this.title,
          content: this.content,
          status: this.status, // Include status, Programmer: Cherry Romandiaz
        }, {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });

        this.success = 'Post created successfully!';
        this.title = '';
        this.content = '';
        this.status = 'draft'; // Reset to default, Programmer: Cherry Romandiaz
        this.$emit('post-created'); // Emit the event to update the dashboard, Programmer: Cherry Romandiaz
        this.$emit('close'); // Close the modal, Programmer: Cherry Romandiaz
      } catch (error) {
        if (error.response) {
          this.error = error.response.data.message || 'Failed to create post';
        } else {
          this.error = 'Failed to communicate with the server';
        }
      }
    }
  }
};
</script>
