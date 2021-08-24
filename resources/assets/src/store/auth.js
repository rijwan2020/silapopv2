
export default {
    namespaced: true,
    state: {
        user: false,
        role: false,
        token: false,
    },
    mutations: {
        set: (state, payload) => {
            state.user = payload.data
        },
        token: (state, payload) => {
            state.token = payload.token
        },
        setRole: (state, payload) => {
            state.role = payload
        },
    },
    actions: {
        set: ({ commit }, payload) => {
            commit('set', payload)
            commit('token', payload)
        },
        role: ({ commit }, payload) => {
            commit('setRole', payload)
        },
    },
    getters: {
        user: state => state.user,
        role: state => state.role,
        token: state => state.token,
    }
}
