const mutations = {
    // General
    changeShowView(state, n) {
        state.config.users.show_view_state = n;
    },

    //Limpiar error usuario
    clearError(state) {
        state.errors.code = 0;
    },
}

export default mutations;
