<template>
  <ul class="flex justify-center">
    <li
      class="mx-1 px-3 py-2 bg-gray-800 text-white rounded-lg"
      :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
    >
      <button
        class="flex items-center font-bold text-white"
        :disabled="currentPage === 1"
        @click="changePage(currentPage - 1)"
      >
        Previous
      </button>
    </li>
    <li
      v-for="page in pages"
      :key="page"
      class="mx-1 px-3 py-2 bg-gray-800 text-white hover:bg-gray-700 hover:text-white rounded-lg"
      :class="{ 'bg-gray-700 text-white': currentPage === page }"
    >
      <button class="font-bold" @click="changePage(page)">{{ page }}</button>
    </li>
    <li
      class="mx-1 px-3 py-2 bg-gray-800 text-white rounded-lg"
      :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }"
    >
      <button
        class="flex items-center font-bold text-white"
        :disabled="currentPage === lastPage"
        @click="changePage(currentPage + 1)"
      >
        Next
      </button>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
  },
  computed: {
    pages() {
      const range = [];
      for (let i = 1; i <= this.lastPage; i++) {
        range.push(i);
      }
      return range;
    },
  },
  methods: {
    changePage(page) {
      if (page !== this.currentPage) {
        this.$emit('page-changed', page);
      }
    },
  },
};
</script>

<style scoped>
/* Add your Tailwind CSS styles here if needed */
</style>
