<template>
  <div class="font-roboto bg-gray-100 min-h-screen flex flex-col">
    <navbar></navbar>
    <div class="flex-grow container mx-auto p-4">
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-8">
          <h2 class="text-2xl font-bold text-gray-700 mb-4">Manage Comments</h2>
          <div v-if="comments.length">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      <input 
                        type="checkbox" 
                        :checked="isAllSelected"
                        @change="toggleSelectAll"
                        class="mr-2"
                      />
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="comment in comments" :key="comment.id">
                    <td class="px-4 py-4 whitespace-nowrap">
                      <input 
                        type="checkbox" 
                        :value="comment.id" 
                        v-model="selectedComments"
                      />
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ comment.content }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ comment.name }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ comment.email }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="flex gap-4 mt-4">
              <button
                @click="deleteSelectedComments"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
              >
                Delete Selected
              </button>
              <router-link 
                to="/dashboard" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >
                Back to Dashboard
              </router-link>
            </div>
          </div>
          <div v-else>
			<p class="text-gray-700 mb-4">No comments available.</p>
            <router-link 
              to="/dashboard" 
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Back to Dashboard
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <simple-footer></simple-footer>
  </div>
</template>

<script>
import Navbar from "@/components/NavBarComponent.vue";
import SimpleFooter from "@/components/FooterComponent.vue";
import axios from 'axios';

export default {
  name: 'ManageComments',
  components: {
    Navbar,
    SimpleFooter
  },
  data() {
    return {
      comments: [],
      selectedComments: [], // Array of selected comment IDs, Programmer: Cherry Romandiaz
    };
  },
  computed: {
    isAllSelected() {
      return this.selectedComments.length === this.comments.length;
    }
  },
  async created() {
    await this.fetchComments();
  },
  methods: {
    async fetchComments() {
      const postId = this.$route.params.post_id; // Get the post ID from route params
      if (!postId) {
        console.error('Post ID is missing from route params');
        return;
      }
      const token = localStorage.getItem('token');
      try {
        const response = await axios.get(`http://localhost:8000/api/posts/${postId}/comments`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.comments = response.data;
      } catch (error) {
        console.error('Error fetching comments:', error); // Log any errors
      }
    },
    toggleSelectAll(event) {
      const checked = event.target.checked;
      if (checked) {
        this.selectedComments = this.comments.map(comment => comment.id);
      } else {
        this.selectedComments = [];
      }
    },
    async deleteSelectedComments() {
      const postId = this.$route.params.post_id;
      if (!postId) {
        console.error('Post ID is missing from route params');
        return;
      }
      const token = localStorage.getItem('token');
      try {
        await axios.post(`http://localhost:8000/api/posts/${postId}/comments/delete`, {
          comment_ids: this.selectedComments
        }, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.comments = this.comments.filter(comment => !this.selectedComments.includes(comment.id));
        this.selectedComments = [];
      } catch (error) {
        console.error('Error deleting comments:', error); // Log any errors
      }
    }
  }
};
</script>

<style scoped>
.table-container {
  max-width: 100%;
  overflow-x: auto;
}
</style>
