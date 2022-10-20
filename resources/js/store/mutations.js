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

        var array_dates = [];
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

                //Guardamos ya formateado las fechas para las columnas de la tabla
                articles_obj.articles.map(function(article, key) {
                    article.dates.map(function(date, key) {
                        var new_date_1 = changeFormatDate(date);
                        if(!array_dates.includes(new_date_1)){
                            array_dates.push(new_date_1);
                        }
                    });
                });
            });
        }

        state.proposals.proposal_obj.array_dates = array_dates;
        state.proposals.proposal_obj.is_change = true;
    },

    changeValueIsChangeArticle(state){
        state.proposals.proposal_obj.is_change = false;
    },
}

//UTILS
function changeFormatDate(date){
    var date_aux = date.split('-');
    var new_date = date_aux[1] + '-' + date_aux[0] + '-' +  date_aux[2];
    var mydate = new Date(new_date);
    var month = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN",
    "JUL", "AGO", "SEP", "OCT", "NOV", "DEC"][mydate.getMonth()];
    var str = month + mydate.getYear().toString().substr(-2);
    return str;
}

export default mutations;
