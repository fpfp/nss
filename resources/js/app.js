require('./bootstrap');

window.Vue = require('vue');

//Import progressbar
require('./progressbar');

//Import events
require('./events');

import store from "./store";

//Import View Router
import VueRouter from 'vue-router'

//Import moment
Vue.use(require('vue-moment'));


//Import Sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
    toast: true,
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
})
window.Toast = Toast

//Import v-from
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


//Import Router
import router from './router';

import Axios from "axios";


Vue.component('nav-component', require('./components/NavComponent.vue').default);



//Create a fresh Vue application instance

const app = new Vue({
    el: '#app',
    store: store,
    router: router,
    mounted() {
        this.$Progress.finish()
    },
    created() {

        this.$Progress.start()

        const user = localStorage.getItem('user')

        if (user) {
            const userData = JSON.parse(user)
            this.$store.commit('setUserData', userData)
        }

        
        //  hook before we move router-view
        this.$router.beforeEach((to, from, next) => {
            this.$Progress.start()
            next()
        })
        //  hook after we've finished moving router-view
        this.$router.afterEach((to, from) => {
            this.$Progress.finish()
        })


        // intercept 403 error and redirect
        axios.interceptors.response.use(
            response => response,
            error => {
                console.log(this.$route.name);
                if (error.response.status === 403) {
                    this.$store.dispatch('logout')
                }
                return Promise.reject(error)
            }

        )

    }
});
