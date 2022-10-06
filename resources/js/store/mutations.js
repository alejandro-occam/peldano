const mutations = {
    // General
    changeShowView(state, n) {
        state.config.users.show_view_state = n;
    },
}

export default mutations;
