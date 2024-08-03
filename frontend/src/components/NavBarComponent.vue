<template>
  <div>
    <nav class="bg-white px-6 py-4 shadow">
      <div class="flex flex-col container mx-auto md:flex-row md:items-center md:justify-between">
        <div class="flex justify-between items-center">
          <div>
            <router-link class="text-gray-800 text-xl font-bold md:text-2xl" to="/">
              Simple <span class="text-blue-500">Blog</span>
            </router-link>
          </div>
          <div>
            <button type="button" @click="isOpen = !isOpen" class="block text-gray-800 hover:text-gray-600 focus:text-gray-600 focus:outline-none md:hidden">
              <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                <path d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2z"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="flex flex-col md:flex-row md:-mx-4" :class="isOpen ? 'block' : 'hidden md:block'">
          <template v-if="!isLoggedIn">
            <input 
              type="text" 
              v-model="searchQuery" 
              @input="emitSearchQuery" 
              placeholder="Search posts..." 
              class="my-1 text-gray-800 md:mx-4 md:my-0 px-4 py-2 rounded border border-gray-300 focus:outline-none focus:shadow-outline"
            />
          </template>
          <template v-if="isLoggedIn">
            <span class="my-1 text-gray-800 md:mx-4 md:my-0 font-bold">Hi, {{ name }}</span>
            <button @click="logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Logout</button>
          </template>
          <template v-else>
            <router-link class="my-1 text-gray-800 hover:text-blue-500 md:mx-4 md:my-0" to="/login">Login</router-link>
            <router-link class="my-1 text-gray-800 hover:text-blue-500 md:mx-4 md:my-0" to="/register">Register</router-link>
          </template>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isOpen: false,
      isLoggedIn: false,
      name: '',
      searchQuery: '',
    };
  },
  created() {
    this.checkAuthStatus();
  },
  methods: {
    checkAuthStatus() {
      const token = localStorage.getItem('token');
      const name = localStorage.getItem('name');
      if (token && name) {
        this.isLoggedIn = true;
        this.name = name;
      }
    },
    logout() {
      localStorage.removeItem('token');
      localStorage.removeItem('name');
      this.isLoggedIn = false;
      this.$router.push('/login');
    },
    emitSearchQuery() {
      this.$emit('search-query', this.searchQuery); // Emit search query to parent, Programmer: Cherry Romandiaz
    }
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
