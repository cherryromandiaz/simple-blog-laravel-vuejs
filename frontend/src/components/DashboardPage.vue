<template>
  <div class="font-roboto bg-gray-100 min-h-screen flex flex-col">
    <navbar></navbar>
    <div class="flex-grow container mx-auto p-4">
      <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
          <div class="w-full p-8">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-2xl font-bold text-gray-700">Dashboard</h2>
              <button
                @click="showCreatePostModal = true"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              >
                Create New Post
              </button>
            </div>
            <div class="mb-4">
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Search posts by title or content"
                class="w-full p-2 border rounded"
              />
            </div>
            <div v-if="filteredPosts.length">
              <ul>
                <li v-for="post in paginatedPosts" :key="post.post_id" class="border p-4 mb-4 rounded-lg">
                  <div class="flex justify-between items-center mb-2">
                    <div>
                      <h3 class="text-xl font-bold">{{ post.title }}</h3>
                      <p class="text-gray-500">Status: <span :class="statusClass(post.status)">{{ post.status }}</span></p>
                    </div>
                    <div class="flex space-x-2">
                      <button
                        @click="openEditPostModal(post)"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                      >
                        Edit
                      </button>
                      <button
                        @click="confirmDelete(post.post_id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                      >
                        Delete
                      </button>
                      <router-link
                        :to="`/manage-comments/${post.post_id}`"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                      >
                        Manage Comments
                      </router-link>
                    </div>
                  </div>
                </li>
              </ul>
              <div class="mt-4 flex justify-between">
                <button
                  :disabled="currentPage === 1"
                  @click="currentPage--"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                  Previous
                </button>
                <button
                  :disabled="currentPage === totalPages"
                  @click="currentPage++"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                  Next
                </button>
              </div>
            </div>
            <div v-else>
              <p class="text-gray-700">No posts available.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <simple-footer></simple-footer>
    <modal-component :isOpen="showCreatePostModal" @close="showCreatePostModal = false">
      <create-post @post-created="handlePostCreated" @close="showCreatePostModal = false"></create-post>
    </modal-component>
    <modal-component :isOpen="showEditPostModal" @close="closeEditPostModal">
      <edit-post :post="selectedPost" :post_id="selectedPost?.post_id" @post-updated="handlePostUpdated" @close="closeEditPostModal"></edit-post>
    </modal-component>
    <modal-component :isOpen="showConfirmationModal" @close="showConfirmationModal = false">
      <div class="text-center">
        <h3 class="text-lg font-bold mb-4">Are You Sure You Want To Delete This Post?</h3>
        <div class="flex justify-center space-x-2">
          <button
            @click="deletePost"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
          >
            Yes
          </button>
          <button
            @click="showConfirmationModal = false"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
          >
            No
          </button>
        </div>
      </div>
    </modal-component>
  </div>
</template>

<script>
import Navbar from "@/components/NavBarComponent.vue";
import SimpleFooter from "@/components/FooterComponent.vue";
import ModalComponent from "@/components/ModalComponent.vue";
import CreatePost from "@/components/CreatePost.vue";
import EditPost from "@/components/EditPost.vue";
import axios from 'axios';

export default {
  name: 'DashboardPage',
  components: {
    Navbar,
    SimpleFooter,
    ModalComponent,
    CreatePost,
    EditPost
  },
  data() {
    return {
      posts: [],
      showCreatePostModal: false,
      showEditPostModal: false,
      showConfirmationModal: false,
      postIdToDelete: null,
      selectedPost: null,
      currentPage: 1,
      pageSize: 5,
      searchQuery: ''
    };
  },
  async created() {
    await this.fetchPosts();
  },
  computed: {
    sortedPosts() {
      return [...this.posts].sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
    },
    filteredPosts() {
      if (!this.searchQuery) {
        return this.sortedPosts;
      }
      const query = this.searchQuery.toLowerCase();
      return this.sortedPosts.filter(post => 
        post.title.toLowerCase().includes(query) || post.content.toLowerCase().includes(query)
      );
    },
    totalPages() {
      return Math.ceil(this.filteredPosts.length / this.pageSize);
    },
    paginatedPosts() {
      const start = (this.currentPage - 1) * this.pageSize;
      const end = start + this.pageSize;
      return this.filteredPosts.slice(start, end);
    }
  },
  methods: {
    async fetchPosts() {
      const token = localStorage.getItem('token');
      try {
        const response = await axios.get('http://localhost:8000/api/user/posts', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.posts = response.data;
      } catch (error) {
        if (error.response && error.response.status === 401) {
          this.$router.push('/login');
        }
      }
    },
    async deletePost() {
      const token = localStorage.getItem('token');
      try {
        await axios.delete(`http://localhost:8000/api/posts/${this.postIdToDelete}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        await this.fetchPosts();
        this.showConfirmationModal = false;
      } catch (error) {
        // Handle error gracefully, Programmer: Cherry Romandiaz
      }
    },
    statusClass(status) {
      switch (status) {
        case 'published':
          return 'text-green-500';
        case 'draft':
          return 'text-gray-500';
        default:
          return 'text-red-500';
      }
    },
    handlePostCreated() {
      this.fetchPosts();
    },
    openEditPostModal(post) {
      this.selectedPost = post;
      this.showEditPostModal = true;
    },
    closeEditPostModal() {
      this.selectedPost = null;
      this.showEditPostModal = false;
    },
    handlePostUpdated() {
      this.fetchPosts();
    },
    confirmDelete(postId) {
      this.postIdToDelete = postId;
      this.showConfirmationModal = true;
    },
    logout() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    },
  },
};
</script>

<style scoped>
/* Add your Tailwind CSS styles here if needed */
</style>
