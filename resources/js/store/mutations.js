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
            dates_prices_aux: [],
            dates_prices: [],
        };

        var array_dates = [], array_dates_aux = [];
        if(state.proposals.proposal_obj.products.length > 0){
            if(state.proposals.proposal_obj.products[0].product_obj == null){
                state.proposals.proposal_obj.products[0].product_obj = params.product_obj
            }
            var exist_product = false;
            var exist_product_key = 0;
            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                if(articles_obj.product_obj.id == params.product_obj.id){
                    exist_product = true;   
                    exist_product_key = key;                 
                }
            });

            if(exist_product){
                state.proposals.proposal_obj.products[exist_product_key].articles_aux.push(article);
                
            }else{
                var product = {
                    product_obj: params.product_obj,
                    articles_aux: [article]
                }
                state.proposals.proposal_obj.products.push(product);
            }

            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                articles_obj.articles = [];
                articles_obj.articles_aux.dates_prices_aux = [];
                //Guardamos ya formateado las fechas para las columnas de la tabla
                articles_obj.articles_aux.map(function(article, key) {
                    article.dates_prices_aux = [];
                    article.dates.map(function(date, key) {
                        array_dates_aux.push(date);
                    });
                });
            });

            //Agrupamos los artículos
            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                articles_obj.articles = [];
                var array_articles = [];
                articles_obj.articles_aux.map(function(article, key) {
                    if(key == 0){
                        article.dates.map(function(date, key) {
                            var date = {
                                'date': date,
                                'pvp': article.article_obj.pvp
                            }
                            article.dates_prices_aux.push(date);
                        });
                       
                        array_articles.push(article);
                    }else{
                        var exist = false;
                        array_articles.map(function(article_arr, key) {
                            if(article_arr.article_obj.id == article.article_obj.id){
                                exist = true;
                                article.dates.map(function(date, key) {
                                    var date = {
                                        'date': date,
                                        'pvp': article.article_obj.pvp
                                    }
                                    article_arr.dates_prices_aux.push(date);
                                });
                            }
                            if(!exist){
                                array_articles.push(article);
                            }
                        });
                    }
                });
                
                array_articles.map(function(article_obj, key) {
                    articles_obj.articles.push(article_obj);
                });
            });

            //Ordenamos las fechas
            array_dates_aux = array_dates_aux.sort(function(a,b){
                var b_aux = Date.parse(new Date(changeFormatDate2(b)));
                var a_aux = Date.parse(new Date(changeFormatDate2(a)));
                return a_aux - b_aux;
            });

            //Modificamos el formato de las fechas
            array_dates_aux.map(function(date, key) {
                var new_date_1 = changeFormatDate(date);
                if(!array_dates.includes(new_date_1)){
                    array_dates.push(new_date_1);
                }
            });

            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                articles_obj.articles.map(function(article_finish, key) {
                    article_finish.dates_prices = [];
                });
            });

            array_dates.map(function(date, key) {
                state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                    articles_obj.articles.map(function(article_finish, key) {
                        articles_obj.articles_aux.map(function(article, key) {
                            article.dates_prices_aux.map(function(date_aux, key) {
                                if(changeFormatDate(date_aux.date) == date){
                                    //Empezamos a trabajar aquí
                                    if(article_finish.dates_prices.length == 0){
                                        var date_obj = {
                                            date: date,
                                            arr_pvp: [date_aux.pvp]
                                        }
                                        article_finish.dates_prices.push(date_obj);

                                    }else{
                                        article_finish.dates_prices.map(function(d_p, key) {
                                            if(d_p.date == date){
                                                d_p.arr_pvp.push(date_aux.pvp);
                                            }else{
                                                var date_obj = {
                                                    date: date,
                                                    arr_pvp: [date_aux.pvp]
                                                }
                                                article_finish.dates_prices.push(date_obj);
                                            }
                                        });
                                    }
                                }
                            });
                        });
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

function changeFormatDate2(date){
    var date_aux = date.split('-');
    var new_date = date_aux[2] + '-' + date_aux[1] + '-' + date_aux[0];
    return new_date;
}

export default mutations;
