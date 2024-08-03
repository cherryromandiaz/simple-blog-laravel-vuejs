<template>
  <div class="font-roboto bg-gray-100 min-h-screen flex flex-col">
    <navbar></navbar>
    <div class="flex-grow container mx-auto p-4">
      <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
        <div class="md:flex">
          <div class="w-full p-8">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Login</h2>
            <form @submit.prevent="login">
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input
                  type="email"
                  v-model="email"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  required
                />
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input
                  type="password"
                  v-model="password"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  required
                />
              </div>
              <div class="flex items-center justify-between">
                <button
                  type="submit"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                  Login
                </button>
              </div>
            </form>
            <div v-if="error" class="text-red-500 mt-4">{{ error }}</div>
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
  name: 'LoginPage',
  components: {
    Navbar,
    SimpleFooter
  },
  data() {
    return {
      email: '',
      password: '',
      error: null
    };
  },
  methods: {
    async login() {
      try {
        // Fetch the CSRF token first
        //await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
        
        const response = await axios.post('http://localhost:8000/api/login', {
          email: this.email,
          password: this.password
        }, {
          headers: {
            'Content-Type': 'application/json'
          },
          withCredentials: true
        });
        
        localStorage.setItem('token', response.data.token);
		localStorage.setItem('name', response.data.name);
        this.$router.push('/dashboard');
      } catch (error) {
        if (error.response) {
          // Log the full error response for debugging, Programmer: Cherry Romandiaz
		// eslint-disable-next-line no-console
          console.error('Error response data:', error.response.data);
		// eslint-disable-next-line no-console
          console.error('Error response status:', error.response.status);
          this.error = error.response.data.message || 'Invalid email or password';
        } else {
		// eslint-disable-next-line no-console
          console.error('Error message:', error.message);
          this.error = 'Failed to communicate with the server';
        }
      }
    },
  },
};
</script>

<style scoped>
/* Add your Tailwind CSS styles here if needed */
</style>
