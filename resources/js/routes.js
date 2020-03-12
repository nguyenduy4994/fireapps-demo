import AuthForm from "./components/Shopify/Auth/AuthForm.vue";
import AuthLogout from "./components/Shopify/Auth/AuthLogout";
import AuthProcessing from "./components/Shopify/Auth/AuthProcessing";

import Home from "./components/Shopify/Shop/Home";

export const routes = [
    {
        name: 'shop.home',
        path: '/',
        component: Home
    },
    {
        name: 'auth.form',
        path: '/auth/form',
        component: AuthForm
    },
    {
        name: 'auth.logout',
        path: '/auth/logout',
        component: AuthLogout
    },
    {
        name: 'auth.processing',
        path: '/auth',
        component: AuthProcessing
    }
];
