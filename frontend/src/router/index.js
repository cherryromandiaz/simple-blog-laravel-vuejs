// router/index.js or router/index.ts, Programmer: Cherry Romandiaz
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/HomePage.vue';
import Login from '../components/LoginPage.vue';
import Register from '../components/RegisterPage.vue';
import Dashboard from '../components/DashboardPage.vue';
import CreatePost from '../components/CreatePost.vue';
import EditPost from '../components/EditPost.vue';
import PostDetail from '../components/PostDetail.vue';
import ManageComments from '../components/ManageComments.vue';


const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/create-post',
    name: 'CreatePost',
    component: CreatePost
  },
  {
    path: '/posts/:post_id/edit',
    name: 'EditPost',
    component: EditPost,
    props: true
  },
  {
    path: '/posts/:post_id',
    name: 'PostDetail',
    component: PostDetail,
    props: true
  },
  {
    path: '/manage-comments/:post_id',
    name: 'ManageComments',
    component: ManageComments,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
