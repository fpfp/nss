import Axios from "axios";
import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null,
    },
    getters: {
        isLogged: state => !!state.user
    },
    mutations: {
        setUserData(state, userData) {
            state.user = userData;
            localStorage.setItem('user', JSON.stringify(userData));
            axios.defaults.headers.common.Authorization = `Bearer ${userData.token}`;
        },
        clearUserData(state) {
            localStorage.removeItem("user");
            state.user = null;
            window.location.href = '/';
        },
    },
    actions: {

        login({ commit }, credentials) {
            return axios
                .post("/api/login", credentials)
                .then((response) => {
                    // console.log("User signed in");
                    // console.log(response);
                    const userData = response.data.user;
                    userData.token = response.data.token;
                    commit('setUserData', userData);
                })
        },
        logout({ commit }) {
            commit('clearUserData');
        }

    }
});
