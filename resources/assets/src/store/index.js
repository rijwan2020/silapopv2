import Vue from "vue";
import Vuex from "vuex";
import VuexPersist from 'vuex-persist'
const vuexPersist = new VuexPersist({
    key: 'my-app',
    storage: localStorage
})

Vue.use(Vuex);

import auth from '../store/auth';

export default new Vuex.Store({
    plugins: [vuexPersist.plugin],
    state: {
        params: {}
    },
    mutations: {
        setParam: (state, payload) => {
            state.params = payload
        },
    },
    actions: {
        param: ({ commit }, payload) => {
            commit('setParam', payload)
        },
    },
    getters: {
    },
    modules: {
        auth
    }
})
