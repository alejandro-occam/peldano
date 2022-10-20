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
        var article = {
            area: params.select_area,
            sector_obj:params.sector_obj,
            brand: params.select_brand,
            article_obj: params.article_obj,
            amount: params.amount,
            dates: params.dates,
        };

        if(state.proposals.proposal_obj.products.length > 0){
            if(state.proposals.proposal_obj.products[0].product_obj == null){
                state.proposals.proposal_obj.products[0].product_obj = params.product_obj
            }
            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                if(articles_obj.product_obj.id == params.product_obj.id){
                    if(articles_obj.articles.length > 0){
                        if(articles_obj.articles[0].article_obj != null){
                            if(articles_obj.articles[0].article_obj.id == params.article_obj.id){
                                articles_obj.articles.push(article);
        
                            }
                        }else{
                            articles_obj.articles.shift();
                            articles_obj.articles.push(article);
                        }
                    }else{
                        articles_obj.articles.push(article);
                    }
                }else{
                    var product = {
                        product_obj: params.product_obj,
                        articles: [article]
                    }
                    state.proposals.proposal_obj.products.push(product);
                }
            });
        }
    }
}

export default mutations;
