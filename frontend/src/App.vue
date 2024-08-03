<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'App',
};
</script>

<style>
    @import "assets/css/app.css";

    ::-webkit-scrollbar {
        width: 5px;
    }

    ::-webkit-scrollbar-track {
        background: #2D3748;
    }

    ::-webkit-scrollbar-thumb {
        background: #CBD5E0;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #2D3748;
    }
</style>
