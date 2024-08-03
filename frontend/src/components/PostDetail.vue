<template>
  <div class="font-roboto bg-gray-100 min-h-screen">
    <navbar></navbar>
    <div class="px-6 py-8">
      <div class="container mx-auto">
        <div class="w-full lg:w-8/12 mx-auto bg-white p-6 rounded-lg shadow-lg">
          <!-- Conditional rendering to handle case when post is null -->
			<router-link to="/" class="text-blue-500 hover:underline mt-4 inline-block">Back to Home</router-link>
			<div v-if="post">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ post.title }}</h1>
            <p class="text-gray-700 mt-4">{{ post.content }}</p>
            <div class="mt-4 flex justify-between items-center">
              <!-- Author Name -->
              <p class="text-gray-700 font-medium">Author: {{ post.user.name }}</p>
              <!-- Like and Dislike Buttons -->
              <div class="flex space-x-4">
                <!-- Like Button -->
                <button
                  @click="likePost"
                  class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 flex items-center space-x-2"
                >
                  <i class="fas fa-thumbs-up"></i>
                  <span>{{ post.likes }}</span>
                </button>
                <!-- Dislike Button -->
                <button
                  @click="dislikePost"
                  class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 flex items-center space-x-2"
                >
                  <i class="fas fa-thumbs-down"></i>
                  <span>{{ post.dislikes }}</span>
                </button>
              </div>
            </div>
            
            <!-- Comments Section -->
            <div class="mt-6">
              <h2 class="text-2xl font-bold text-gray-800 mb-4">Comments</h2>
              <div v-for="comment in comments" :key="comment.id" class="mb-4 p-4 bg-gray-100 rounded-lg shadow">
                <p class="font-semibold">{{ comment.name }} ({{ comment.email }})</p>
                <p>{{ comment.content }}</p>
                <!-- Like and Dislike Buttons for Comments -->
                <div class="flex space-x-4 mt-2">
                  <button
                    @click="likeComment(comment.id)"
                    class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 flex items-center space-x-2"
                  >
                    <i class="fas fa-thumbs-up"></i>
                    <span>{{ comment.likes }}</span>
                  </button>
                  <button
                    @click="dislikeComment(comment.id)"
                    class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 flex items-center space-x-2"
                  >
                    <i class="fas fa-thumbs-down"></i>
                    <span>{{ comment.dislikes }}</span>
                  </button>
                </div>
              </div>
              <form @submit.prevent="addComment" class="mt-6">
                <div class="mb-4">
                  <label for="name" class="block text-gray-700">Name</label>
                  <input type="text" v-model="newComment.name" id="name" class="w-full px-3 py-2 border rounded-lg">
                  <span v-if="errors.name" class="text-red-600">{{ errors.name }}</span>
                </div>
                <div class="mb-4">
                  <label for="email" class="block text-gray-700">Email</label>
                  <input type="email" v-model="newComment.email" id="email" class="w-full px-3 py-2 border rounded-lg">
                  <span v-if="errors.email" class="text-red-600">{{ errors.email }}</span>
                </div>
                <div class="mb-4">
                  <label for="content" class="block text-gray-700">Comment</label>
                  <textarea v-model="newComment.content" id="content" class="w-full px-3 py-2 border rounded-lg"></textarea>
                  <span v-if="errors.content" class="text-red-600">{{ errors.content }}</span>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">Submit</button>
              </form>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <simple-footer></simple-footer>
  </div>
</template>

<script>
import axios from 'axios';
import Navbar from "@/components/NavBarComponent.vue";
import SimpleFooter from "@/components/FooterComponent.vue";

export default {
  name: 'PostDetail',
  components: {
    Navbar,
    SimpleFooter,
  },
  data() {
    return {
      post: null,
      comments: [],
      newComment: {
        name: '',
        email: '',
        content: '',
      },
      errors: {
        name: '',
        email: '',
        content: '',
      },
      errorMessage: '',
    };
  },
  async created() {
    await this.fetchPost();
    await this.fetchComments();
  },
  methods: {
    async fetchPost() {
      const token = localStorage.getItem('token');
      const postId = this.$route.params.post_id;
      try {
        const response = await axios.get(`http://localhost:8000/api/posts/${postId}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.post = response.data;
      } catch (error) {
        this.errorMessage = 'Failed to load the post. Please try again later.';
      }
    },
    async fetchComments() {
      const postId = this.$route.params.post_id;
      try {
        const response = await axios.get(`http://localhost:8000/api/posts/${postId}/comments`);
        this.comments = response.data;
      } catch (error) {
        this.errorMessage = 'Failed to load comments. Please try again later.';
      }
    },
    async likePost() {
      try {
        const response = await axios.post(`http://localhost:8000/api/posts/${this.post.post_id}/like`);
        this.post.likes = response.data.post.likes;
      } catch (error) {
        // Programmer: Cherry Romandiaz
      }
    },
    async dislikePost() {
      try {
        const response = await axios.post(`http://localhost:8000/api/posts/${this.post.post_id}/dislike`);
        this.post.dislikes = response.data.post.dislikes;
      } catch (error) {
        //console.error('Failed to dislike post:', error);
      }
    },
    async likeComment(commentId) {
      try {
        const response = await axios.post(`http://localhost:8000/api/comments/${commentId}/like`);
        const comment = this.comments.find(c => c.id === commentId);
        if (comment) {
          comment.likes = response.data.comment.likes;
        }
      } catch (error) {
        //console.error('Failed to like comment:', error);
      }
    },
    async dislikeComment(commentId) {
      try {
        const response = await axios.post(`http://localhost:8000/api/comments/${commentId}/dislike`);
        const comment = this.comments.find(c => c.id === commentId);
        if (comment) {
          comment.dislikes = response.data.comment.dislikes;
        }
      } catch (error) {
        //console.error('Failed to dislike comment:', error);
      }
    },
    validateForm() {
      this.errors = {
        name: '',
        email: '',
        content: '',
      };

      if (!this.newComment.name) {
        this.errors.name = 'Name is required.';
      }

      if (!this.newComment.email) {
        this.errors.email = 'Email is required.';
      } else if (!this.validEmail(this.newComment.email)) {
        this.errors.email = 'Email must be valid.';
      }

      if (!this.newComment.content) {
        this.errors.content = 'Comment is required.';
      }

      return !this.errors.name && !this.errors.email && !this.errors.content;
    },
    validEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    },
    async addComment() {
      if (!this.validateForm()) {
        return;
      }

      const postId = this.$route.params.post_id;
      try {
        const response = await axios.post(`http://localhost:8000/api/posts/${postId}/comments`, this.newComment);
        this.comments.push(response.data);
        this.newComment = { name: '', email: '', content: '' };
      } catch (error) {
        this.errorMessage = 'Failed to add comment. Please try again later.';
      }
    },
  },
};
</script>

<style scoped>
/* Add your Tailwind CSS styles here if needed */
</style>
