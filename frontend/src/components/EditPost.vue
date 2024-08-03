<template>
  <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl mt-10">
    <div class="md:flex">
      <div class="w-full p-8">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Edit Post</h2>
        <form @submit.prevent="updatePost">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
              Title
            </label>
            <input
              v-model="editablePost.title"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="title"
              type="text"
              placeholder="Title"
            />
            <p v-if="errors.title" class="text-red-500 text-xs italic">{{ errors.title }}</p>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
              Content
            </label>
            <textarea
              v-model="editablePost.content"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="content"
              rows="10"
              placeholder="Content"
            ></textarea>
            <p v-if="errors.content" class="text-red-500 text-xs italic">{{ errors.content }}</p>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
              Status
            </label>
            <select
              v-model="editablePost.status"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="status"
            >
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
          </div>
          <div class="flex items-center space-x-2">
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              type="submit">
              Update
            </button>
            <button
              @click="$emit('close')"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              type="button"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'EditPost',
  props: {
    post: {
      type: Object,
      required: true
    },
    post_id: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      editablePost: {
        title: this.post.title,
        content: this.post.content,
        status: this.post.status
      },
      errors: {}
    };
  },
  methods: {
    async updatePost() {
      this.errors = this.validatePost(this.editablePost);

      if (Object.keys(this.errors).length === 0) {
        const token = localStorage.getItem('token');
        try {
          await axios.put(`http://localhost:8000/api/posts/${this.post_id}`, this.editablePost, {
            headers: {
              Authorization: `Bearer ${token}`
            }
          });
          this.$emit('post-updated');
          this.$emit('close');
        } catch (error) {
          // Handle error, Programmer: Cherry Romandiaz
        }
      }
    },
    validatePost(post) {
      const errors = {};
      if (!post.title) {
        errors.title = 'Title is required.';
      }
      if (!post.content) {
        errors.content = 'Content is required.';
      }
      return errors;
    }
  }
};
</script>

<style scoped>
/* Add your Tailwind CSS styles here if needed */
</style>
