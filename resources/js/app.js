//: Main Vue 3 JS.

import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import App from "./components/App.vue";
import { routes } from "./routes";
import axios from "axios";
import VueAxios from "vue-axios";

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

const app = createApp(App);

app.use(router);
app.use(VueAxios, axios);

app.config.globalProperties.$axios = axios;
app.config.globalProperties.$router = router;

router.isReady().then(() => app.mount("#app"));

require('./bootstrap');


// ↓ Router.beforeEach... ↓
router.beforeEach((to, from) => {
    let base_path = "/ukf-web-kurier/public/";
    // let base_path = "/";

    // Neprihlaseny
    if (!window.Laravel.isLoggedin) {
        if (to.path === base_path + 'home') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'eshopy') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'pravidla') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'kurieri') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'kurierske_sluzby') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path) {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'editEshop') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'editRule') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'editCourier') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'editUser') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'editService') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'create_user') {
            router.push(base_path + 'login');
        }

        if (to.path === base_path + 'users_overview') {
            router.push(base_path + 'login');
        }
    }

    // Prihlaseny
    if (window.Laravel.isLoggedin) {
        if (to.path === base_path + 'login') {
            router.push(base_path);
        }

        if (to.path === base_path + 'register') {
            router.push(base_path);
        }
    }
});
