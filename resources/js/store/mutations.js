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

        var array_dates = [], array_dates_aux = [], array_dates_prices = [];
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
                                    article.dates_prices_aux.push(date);
                                });
                            }
                        });
                        if(!exist){
                            article.dates.map(function(date, key) {
                                var date = {
                                    'date': date,
                                    'pvp': article.article_obj.pvp
                                }
                                article.dates_prices_aux.push(date);
                            });
                            array_articles.push(article);
                        }
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

            //Borramos el array de fechas anteriormente cargados
            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                articles_obj.articles.map(function(article_finish, key) {
                    article_finish.dates_prices = [];
                });
            });

            //Preparamos los precios y agrupamos por aarticulos y fechas
            array_dates.map(function(date, key) {
                state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                    articles_obj.articles.map(function(article_finish, key) {
                        articles_obj.articles_aux.map(function(article, key) {
                            if(article_finish.article_obj.id == article.article_obj.id){
                                article.amount = 0;
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
                            }
                        });
                    });
                });
            });

            //Consultamos la cantidad de articulos y su total
            var total_global = 0;
            var total_amount_global = 0;
            var total_individual_pvp = 0;
            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                articles_obj.articles.map(function(article_finish, key) {
                    total_individual_pvp += article_finish.article_obj.pvp;
                    article_finish.amount = 0;
                    article_finish.total = 0;
                    article_finish.dates_prices.map(function(date, key) {
                        article_finish.amount += date.arr_pvp.length;
                        total_amount_global += date.arr_pvp.length;
                        date.arr_pvp.map(function(pvp, key) {
                            article_finish.total += pvp;
                            total_global += pvp;
                        });
                    });
                });
            });
            state.proposals.proposal_obj.products.total_global = total_global;
            state.proposals.proposal_obj.products.total_amount_global = total_amount_global;
            state.proposals.proposal_obj.products.total_individual_pvp = total_individual_pvp;


            //Cargamos en el array de fechas para las columnas los totales de cada mes
            array_dates.map(function(date, key) {
                var total_date = 0;
                state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                    articles_obj.articles.map(function(article_finish, key) {
                        article_finish.dates_prices.map(function(date_aux, key) {
                            if(date_aux.date == date){
                                date_aux.arr_pvp.map(function(pvp, key) {
                                    total_date += pvp;
                                });
                            }
                        });
                    });
                });
                var date_obj = {
                    date: date,
                    total: total_date
                }
                array_dates_prices.push(date_obj);
            });
        }

        state.proposals.proposal_obj.array_dates = array_dates_prices;
        state.proposals.proposal_obj.is_change = true;
    },

    changeValueIsChangeArticle(state){
        state.proposals.proposal_obj.is_change = false;
    },

    //Generar propuesta
    generateBill(state){
        var array_articles = [];
        state.proposals.proposal_obj.products.map(function(products, key) {
            products.articles_aux.map(function(article_obj, key) {
                article_obj.dates.map(function(date, key) {
                    var article_obj_aux = {
                        date: date,
                        article: article_obj,
                        id_product: products.product_obj.id
                    }
                    array_articles.push(article_obj_aux);
                });
            });
        });

        //Ordenamos los artículos por fecha
        array_articles = array_articles.sort(function(a,b){
            var b_aux = Date.parse(new Date(changeFormatDate2(b.date)));
            var a_aux = Date.parse(new Date(changeFormatDate2(a.date)));
            return a_aux - b_aux;
        });

        var date_aux = array_articles[0].date;
        var amount = 0;
        var array_finish_bill = [];
        var last_key = 0;
        var total_bill = 0;
        state.proposals.bill_obj.articles = array_articles;

        array_articles.map(function(article_obj, key) {
            if(key == 0){
                amount = article_obj.article.article_obj.pvp;
                total_bill += article_obj.article.article_obj.pvp;
                var bill_month = {
                    date: date_aux,
                    amount: amount,
                    article: article_obj
                }

                array_finish_bill.push(bill_month);

            }else{
                if(date_aux == article_obj.date){
                    var is_break = false;
                    array_finish_bill.map(function(bill_obj, key) {
                        if(!is_break){
                            if(bill_obj.date == article_obj.date){
                                if(bill_obj.article.id_product == article_obj.id_product){
                                    amount += article_obj.article.article_obj.pvp;
                                    total_bill += article_obj.article.article_obj.pvp;
                                    array_finish_bill[last_key].amount = amount;
                                    is_break = true;

                                }else{
                                    amount = 0;
                                    date_aux =  article_obj.date;
                                    amount += article_obj.article.article_obj.pvp;
                                    total_bill += article_obj.article.article_obj.pvp;
                                    var bill_month = {
                                        date: date_aux,
                                        amount: amount,
                                        article: article_obj
                                    }
                                    array_finish_bill.push(bill_month);
                                    last_key = (array_finish_bill.length - 1);
                                    is_break = true;
                                }

                            }else{
                                amount = 0;
                                date_aux =  article_obj.date;
                                amount += article_obj.article.article_obj.pvp;
                                total_bill += article_obj.article.article_obj.pvp;
                                var bill_month = {
                                    date: date_aux,
                                    amount: amount,
                                    article: article_obj
                                }
                                array_finish_bill.push(bill_month);
                                last_key = (array_finish_bill.length - 1);
                                is_break = true;
                            }
                        }
                    });

                }else{
                    amount = 0;
                    date_aux =  article_obj.date;
                    amount += article_obj.article.article_obj.pvp;
                    total_bill += article_obj.article.article_obj.pvp;
                    var bill_month = {
                        date: date_aux,
                        amount: amount,
                        article: article_obj
                    }
                    array_finish_bill.push(bill_month);
                    last_key = (array_finish_bill.length - 1);
                }
            }
        });
        state.proposals.bill_obj.array_bills = array_finish_bill;
        state.proposals.bill_obj.total_bill = total_bill;
        
    }
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
