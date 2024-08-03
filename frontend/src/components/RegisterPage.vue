<template>
  <div class="font-roboto bg-gray-100 min-h-screen flex flex-col">
    <navbar></navbar>
    <div class="flex-grow container mx-auto p-4">
      <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
        <div class="md:flex">
          <div class="w-full p-8">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Register</h2>
            <form @submit.prevent="register">
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                <input
                  type="text"
                  v-model="name"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  required
                />
              </div>
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
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">Confirm Password</label>
                <input
                  type="password"
                  v-model="password_confirmation"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  required
                />
              </div>
              <div class="flex items-center justify-between">
                <button
                  type="submit"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >
                  Register
                </button>
              </div>
            </form>
            <div v-if="error" class="text-red-500 mt-4">{{ error }}</div>
			<div v-if="success" class="text-green-500 mt-4">{{ success }}</div>
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
  name: 'RegisterPage',
  components: {
    Navbar,
    SimpleFooter
  },
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      error: '',
	success: ''
    };
  },
  methods: {
    async register() {
      if (this.password !== this.password_confirmation) {
        this.error = "Passwords do not match";
        return;
      }
      try {
        const response = await axios.post('http://localhost:8000/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
		password_confirmation: this.password_confirmation,
        });
		localStorage.setItem('token', response.data.token);
		this.success = "Registration successful! Redirecting to login..";
        setTimeout(() => {
          this.$router.push('/login');
        }, 3000);
      } catch (error) {
        if (error.response && error.response.data) {
          this.error = error.response.data.message || 'Failed to register';
        } else {
          this.error = 'Failed to register';
        }
		this.success = '';
		// eslint-disable-next-line no-console, Programmer: Cherry Romandiaz
        console.error('Registration error:', error);
      }
    },
  },
};
</script>

<style scoped>
/* Add your Tailwind CSS styles here if needed */
</style>
