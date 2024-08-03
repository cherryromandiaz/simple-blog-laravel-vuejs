<template>
  <div id="app" class="font-roboto bg-gray-100">
    <navbar @search-query="searchPosts"></navbar>
    <div class="px-6 py-8">
      <div class="flex justify-between container mx-auto">
        <div class="w-full lg:w-8/12">
          <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-700 md:text-2xl">Posts</h1>
            <div>
              <select
                class="block bg-gray-300 text-gray-700 py-2 px-2 rounded-lg focus:outline-none md:py-3"
                v-model="selectedOption"
                @change="fetchPosts"
              >
                <option value="latest">Latest</option>
                <option value="oldest">Oldest</option>
              </select>
            </div>
          </div>
          <div v-if="errorMessage" class="text-red-500 mt-4">
            {{ errorMessage }}
          </div>
          <div class="mt-6" v-for="post in filteredPosts" :key="post.post_id">
            <post :data="post" @post-updated="updatePost"></post>
          </div>
          <div class="mt-8">
            <pagination
              :current-page="pagination.current_page"
              :last-page="pagination.last_page"
              @page-changed="fetchPosts"
            ></pagination>
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
import Post from "@/components/Post.vue";
import Pagination from "@/components/Pagination.vue";
import SimpleFooter from "@/components/FooterComponent.vue";

export default {
  name: 'HomePage',
  components: {
    Navbar,
    Post,
    Pagination,
    SimpleFooter,
  },
  data() {
    return {
      posts: [],
      filteredPosts: [],
      errorMessage: '',
      selectedOption: 'latest', // Default selection
      pagination: {
        current_page: 1,
        last_page: 1,
      },
      searchQuery: '', // For search functionality, Programmer: Cherry Romandiaz
    };
  },
  async created() {
    await this.fetchPosts();
  },
  methods: {
    async fetchPosts(page = 1) {
      const token = localStorage.getItem('token');
      try {
        const response = await axios.get(`http://localhost:8000/api/posts?page=${page}&sort=${this.selectedOption}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.posts = response.data.data; // Paginated data array
        this.pagination.current_page = response.data.current_page;
        this.pagination.last_page = response.data.last_page;
        this.filterPosts(); // Apply search filter
      } catch (error) {
        this.errorMessage = 'Failed to load posts. Please try again later.';
        console.error('Fetch posts error:', error);
      }
    },
    searchPosts(query) {
      this.searchQuery = query;
      this.filterPosts(); // Apply filter on search query
    },
    filterPosts() {
      if (this.searchQuery) {
        this.filteredPosts = this.posts.filter(post =>
          post.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          post.content.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      } else {
        this.filteredPosts = this.posts;
      }
    },
    updatePost(updatedPost) {
      const index = this.posts.findIndex(post => post.post_id === updatedPost.post_id);
      if (index !== -1) {
        this.$set(this.posts, index, updatedPost); // Ensure reactivity
        this.filterPosts(); // Reapply filter after updating post
      }
    }
  },
};
</script>

<style scoped>
/* Add your Tailwind CSS styles here if needed */
</style>
