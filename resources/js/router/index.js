import Vue from 'vue';
import Router from 'vue-router';

import menu from "../components/menu";
import login from "../components/login";
import editUser from "../components/editUser";
import registerUser from "../components/registerUser";
import notFound from "../components/notFound";
import profile from "../components/userProfile";
import confirmAccount from "../components/confirmAccount";
import cookDashboard from "../components/cookDashboard";
import waiterDashboard from "../components/waiterDashboard";
import managerDashboard from "../components/managerDashboard";
import createItem from "../components/createItem";
import editItem from "../components/editItem";
import cashierDashboard from "../components/cashierDashboard";

Vue.use(Router);

export default new Router({

    routes : [
        { 
            path: '/', 
            redirect: '/menu', 
            name: 'root',
        },
        { 
            path: '/menu', 
            component: menu,
            name: 'menu' 
        },
        {
            path: '/login',
            component: login,
            name: 'login'
        },
        {
            path: '/edit/user/:userId',
            component: editUser,
            name: 'editUser',
            props: true,
            meta:{
                requireAuth: true
            }
        },
        {
            path: '/register/user',
            component: registerUser,
            name: 'register',
            meta:{
                requireManager: true,
                requireAuth: true 
            }
        },
        {
            path: '/notFound',
            component: notFound,
            name: 'notFound'
        }, 
        {
            path: '/profile',
            component: profile,
            name: 'profile',
            meta: { requireAuth: true }
        },
        {
            path: '/account/confirm/:token',
            component: confirmAccount,
            name: 'confirm',
            props: true,
        },
        {
            path: '/waiter',
            component: waiterDashboard,
            name: 'wiaterDash',
            meta:{
                requireWaiter: true,
                requireAuth: true 
            }
        },
        {
            path: '/cook',
            component: cookDashboard,
            name: 'cookDash',
            meta:{
                requireCook: true,
                requireAuth: true 
            }
        }, 
        {
            path: '/manager',
            component: managerDashboard,
            name: 'managerDash',
            meta:{
                requireManager: true,
                requireAuth: true 
            }
        },
        {
            path: '/create/item',
            component: createItem,
            name: 'createItem',
            meta:{
                requireManager: true,
                requireAuth: true 
            }
        },
        {
            path: '/edit/item/:itemId',
            component: editItem,
            name: 'editItem',
            props: true,
            meta:{
                requireManager: true,
                requireAuth: true 
            }
        },
        {
            path: '/cashier',
            component: cashierDashboard,
            name: 'cashierDash',
            meta:{
                requireCashier: true,
                requireAuth: true 
            }
        }
    ]
    
});