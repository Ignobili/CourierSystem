import Home from "./components/Home.vue";
import About from "./components/About.vue";
import Eshopy from "./components/Eshopy.vue";
import Kurieri from "./components/Kurieri.vue";
import Pravidla from "./components/Pravidla.vue";
import Login from "./components/LoginForm.vue";
import Register from "./components/RegisterForm.vue";
import CreateUser from "./components/CreateUser";
import UsersOverview from "./components/UsersOverview";
import KurierskeSluzby from "./components/KurierskeSluzby";
import EditRule from "./components/EditRule.vue";
import EditService from "./components/EditService.vue";
import EditEshop from "./components/EditEshop";
import EditCourier from "./components/EditCourier.vue";

let base_path = "/ukf-web-kurier/public/";
//let base_path = "/";

export const routes = [{
        name: 'home',
        path: base_path,
        component: Home
    },
    {
        name: 'about',
        path: base_path + 'about',
        component: About
    },
    {
        name: 'eshopy',
        path: base_path + 'eshopy',
        component: Eshopy
    },
    {
        name: 'editEshop',
        path: base_path + ':id',
        component: EditEshop
    },
    {
        name: 'kurieri',
        path: base_path + 'kurieri',
        component: Kurieri
    },
    {
        name: 'pravidla',
        path: base_path + 'pravidla',
        component: Pravidla
    },
    {
        name: 'editRule',
        path: base_path + ':id',
        component: EditRule
    },
    {
        name: 'editCourier',
        path: base_path + ':id',
        component: EditCourier
    },
    {
        name: 'login',
        path: base_path + 'login',
        component: Login
    },
    {
        name: 'register',
        path: base_path + 'register',
        component: Register
    },
    {
        name: 'create_user',
        path: base_path + 'create_user',
        component: CreateUser
    },
    {
        name: 'users_overview',
        path: base_path + 'users_overview',
        component: UsersOverview
    },
    {
        name: 'kurierske_sluzby',
        path: base_path + 'kurierske_sluzby',
        component: KurierskeSluzby
    },
    {
        name: 'editService',
        path: base_path + ':id',
        component: EditService
    },
];
