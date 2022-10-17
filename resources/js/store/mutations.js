const mutations = {
    // General
    changeShowView(state, n) {
        state.config.users.show_view_state = n;
    },

    changeShowViewCalendar(state, n) {
        state.config.calendars.show_view_state = n;
    },

    //Limpiar error usuario
    clearError(state) {
        state.errors.code = 0;
    },

    //Control form usuarios
    controlFormUsers(state, n) {
        state.config.users.is_update = n;
    },

    //Control form calendarios
    controlFormCalendars(state, n) {
        state.config.calendars.is_update = n;
    },

    //Control form articulos
    controlFormArticles(state, n) {
        state.config.articles.is_update = n;
    },

    saveSearchArticles(state, n){
        state.config.articles.search_articles = n;
    }
}

export default mutations;
