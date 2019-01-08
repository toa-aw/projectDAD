require('./bootstrap');
window.Vue = require('vue');

import store from './stores/global-store';
import FlashMessage from "@smartweb/vue-flash-message";
import router from "./router";
import VueSocketio from 'vue-socket.io';

Vue.use(FlashMessage);
const navBar = Vue.component('navBar', require('./components/navBar.vue'));

Vue.use(new VueSocketio({
  debug: true,
  connection: 'http://167.99.239.35:8080'
 })); 

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requireAuth)) {
      if (store.state.user == null) {
        next({
          path: '/login',
        });
      } else {
        next();
      }
    } else {
      next();
    }
  });


  router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requireManager)) {
      if(store.state.user.type !== 'manager') {
        next({
            path: '/menu',
          });
      }else{
          next();
      }
    } else {
      next();
    }
  });

  router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requireCashier)) {
     if(store.state.user.type !== 'cashier') {
        next({
            path: '/menu',
          });
      }else{
          next();
      }
    } else {
      next();
    }
  });

  router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requireCook)) {
       if(store.state.user.type !== 'cook') {
        next({
            path: '/menu',
          });
      }else{
          next();
      }
    } else {
      next();
    }
  });

  router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requireWaiter)) {
       if(store.state.user.type !== 'waiter') {
        next({
            path: '/menu',
          });
      }else{
          next();
      }
    } else {
      next();
    }
  });

new Vue({
    sockets:{
      connect(){
      console.log('socket connected (socket ID = '+this.$socket.id+')');
      },
      update_orders(order){
        if(order.state === 'confirmed' && store.getters.getType === 2){
          this.flashMessage.info({
            title: "New Order to prepare",
            message: "There is a " + order.item + " that must be prepared"
          });
        }else if(order.state === 'prepared' && store.getters.getType === 3){
          this.flashMessage.info({
            title: "Order has been prepared",
            message: "The " + order.item + " is reade to be deliverd to table " + order.table
          });
        }
      },
      update_invoices(){
        if(store.getters.getType === 4){
          this.flashMessage.info({
            title: "New invoice",
            message: "A new invoice has been created"
          });
        }
      },
     },
    router,
    store,
    created() {
        console.log('-----');
        console.log(this.$store.state.user);
        this.$store.commit('loadTokenAndUserFromSession');
        console.log(this.$store.state.user);
    },
    components : {
        'nav-bar': navBar, 
    }
}).$mount('#app');