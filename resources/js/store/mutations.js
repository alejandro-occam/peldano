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
    },

    changeShowViewArticles(state, n) {
        state.config.articles.show_view_state = n;
    },

    changeViewStatusProposals(state, n){
        state.proposals.status_view = n;
    },

    //Guardar el objeto propuesta
    saveProposalObject(state, params){
        state.proposals.proposal_obj.article.area = params.select_area;
        state.proposals.proposal_obj.article.sector_obj = params.sector_obj;
        state.proposals.proposal_obj.article.brand = params.select_brand;
        state.proposals.proposal_obj.article.product = params.select_product;
        state.proposals.proposal_obj.article.article_obj = params.article_obj;
        state.proposals.proposal_obj.article.amount = params.amount;
        state.proposals.proposal_obj.article.dates = params.dates;
    }
}

export default mutations;
