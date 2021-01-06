import Vue from 'vue'
import VueRouter from 'vue-router'
import Tshirts from './components/TshirtsComponent.vue'
import Editor from './components/EditorComponent.vue'

import store from './store'

Vue.use(VueRouter)

const routes = [
    
    {
        path: '/',
        name: 'Tshirts',
        component: Tshirts
    },
    {
        path: '/editor/:tshirtId', 
        name: 'editor', 
        component: Editor
    }

];


const router = new VueRouter({
    routes,
    base: process.env.BASE_URL,
    mode: 'hash',
})


router.beforeEach((to, from, next) => {

    const user = store.state.user;
    if (to.matched.some(record => record.meta.auth) && !user) {
        next({ name: 'Login' })
    } else {
        next()
    }
    
});

export default router;