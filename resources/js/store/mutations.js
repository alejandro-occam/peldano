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
        //Creamos el objeto artículo que vamos a guardar
        var article = {
            article_obj: params.article_obj,
            dates: params.dates,
            dates_prices_aux: [],
            dates_prices: [],
        };

        //Declaramos variable que utilizaremos después
        var array_dates = [], array_dates_aux = [], array_dates_prices = [];

        //Consultamos si tenemos algún producto creado y si no, lo creamos
        if(state.proposals.proposal_obj.products[0].product_obj == null){
            state.proposals.proposal_obj.products[0].product_obj = params.product_obj
        }

        //Consultamos si existe el producto para nuesto artículo
        var exist_product = false;
        var exist_product_key = 0;
        state.proposals.proposal_obj.products.map(function(articles_obj, key) {
            if(articles_obj.product_obj.id == params.product_obj.id){
                exist_product = true;   
                exist_product_key = key;                 
            }
        });

        //Si existe el producto para nuesto artículo lo añadimos y si no creamos el producto
        if(exist_product){
            state.proposals.proposal_obj.products[exist_product_key].articles_aux.push(article);
            
        }else{
            var product = {
                product_obj: params.product_obj,
                articles_aux: [article]
            }
            state.proposals.proposal_obj.products.push(product);
        }

        //Guardamos las fechas de nuestro artículo en un array e inicializamos variables
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
        state.proposals.proposal_obj.products.map(function(product_obj, key) {
            var array_articles = [];
            //Recorremos los artículos del producto
            product_obj.articles_aux.map(function(article, key) {
                if(key == 0){
                    article.dates.map(function(date, key) {
                        //Guardamos la fecha junto a su precio actual
                        var date = {
                            'date': date,
                            'pvp': article.article_obj.pvp
                        }
                        article.dates_prices_aux.push(date);
                    });
                    
                    //Guardamos el artículo en nuestro array
                    array_articles.push(article);
                }else{
                    var exist = false;
                    //Recorremos nuestro array creado anteriormente donde estamos añadiendo los artículos
                    //Consultamos si existe en nuestro array local el artículo que ha seleccionado el bucle
                    array_articles.map(function(article_arr, key) {
                        //Comparamos los id de los artículos por si es el mismo
                        if(article_arr.article_obj.id == article.article_obj.id){
                            exist = true;
                            var exist2 = false;
                            //Consultamos si alguna de las fechas guardadas en el artículo coincide con el seleccionado por el bucle
                            article_arr.dates_prices_aux.map(function(date_price, key) {
                                article.dates.map(function(date, key) {
                                    if(date_price.date  == changeFormatDate(date)){
                                        date_price.arr_pvp_date.map(function(pvp_date, key) {
                                            if(pvp_date.date == date){
                                                pvp_date.arr_pvp.push(article.article_obj.pvp);
                                                exist2 = true;
                                            }
                                        });
                                    }
                                });
                            });
                            if(!exist2){
                                article.dates.map(function(date, key) {
                                    var date = {
                                        'date': date,
                                        'pvp': article.article_obj.pvp
                                    }
                                    article.dates_prices_aux.push(date);
                                });
                            }
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
            
            //Guardamos los artículos creados en nuestro objeto principal
            array_articles.map(function(article_obj, key) {
                product_obj.articles.push(article_obj);
            });
        });

        //Ordenamos las fechas de forma ascendente
        array_dates_aux = array_dates_aux.sort(function(a,b){
            var b_aux = Date.parse(new Date(changeFormatDate2(b)));
            var a_aux = Date.parse(new Date(changeFormatDate2(a)));
            return a_aux - b_aux;
        });

        //Modificamos el formato de las fechas para las columnas
        array_dates_aux.map(function(date, key) {
            var new_date = changeFormatDate(date);
            if(!array_dates.includes(new_date)){
                array_dates.push(new_date);
            }
        });

        //Borramos el array de fechas anteriormente cargados
        state.proposals.proposal_obj.products.map(function(articles_obj, key) {
            articles_obj.articles.map(function(article_finish, key) {
                article_finish.dates_prices = [];
            });
        });

        //Preparamos los precios y agrupamos por articulos y fechas
        //Recorremos las fechas que habiamos guardado para las columnas y agrupamos según esto
        array_dates.map(function(date, key) {
            //Recorremos los productos
            state.proposals.proposal_obj.products.map(function(articles_obj, key) {
                //Recorremos los artículos anteriormente guardados para guardar el precio con la fecha
                articles_obj.articles.map(function(article_finish, key) {
                    //Recorremos los artículos auxiliares anteriormente guardados
                    articles_obj.articles_aux.map(function(article, key) {
                        //Miramos si son el mismo artículo
                        if(article_finish.article_obj.id == article.article_obj.id){
                            //Inicializamos las cantidad a cero
                            article.amount = 0;
                            //Recorremos las fechas guardadas en el array de articulos auxiliares
                            article.dates_prices_aux.map(function(date_aux, key) {
                                //Comparamos la fecha del articulo auxiliar con la fecha de la columna
                                if(changeFormatDate(date_aux.date) == date){
                                    //Consultamos si el array date_prices de article_finish está vacío
                                    if(article_finish.dates_prices.length == 0){
                                        //Creamos el objeto y lo guardamos
                                        var date_obj = {
                                            date: date, //Fecha columna formateada
                                            arr_pvp_date: [{
                                                date: date_aux.date, //Fecha articulo normal
                                                arr_pvp: [date_aux.pvp] //Precio
                                            }]
                                        }
                                        //Guardamos el objeto en el array
                                        article_finish.dates_prices.push(date_obj);

                                    }else{
                                        //Si el array dates_prices tiene datos lo recorremos
                                        var exist_date_price = false;
                                        article_finish.dates_prices.map(function(d_p, key) {
                                            //Si las fechas de las columnas coinciden entramos
                                            if(d_p.date == date){
                                                //Recorremos el objeto para ver si coinciden tambien las fecha normal
                                                d_p.arr_pvp_date.map(function(p_d, key) {
                                                    if(p_d.date == date_aux.date){
                                                        //Si coincide añadimos al array de la cantidad la cantidad
                                                        p_d.arr_pvp.push(date_aux.pvp);

                                                    }else{
                                                        //Si no coincide creamos un objeto para esta fecha normal
                                                        var p_d_aux = {
                                                            date: date_aux.date,
                                                            arr_pvp: [date_aux.pvp]
                                                        };
                                                        d_p.arr_pvp_date.push(p_d_aux);
                                                    }
                                                    exist_date_price = true;
                                                });
                                            }
                                        });
                                        if(!exist_date_price){
                                            //Si no coinciden creamos el objeto
                                            var date_obj = {
                                                date: date,
                                                arr_pvp_date: [{
                                                    date: date_aux.date,
                                                    arr_pvp: [date_aux.pvp]
                                                }]
                                            }
                                            article_finish.dates_prices.push(date_obj);
                                        }
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
                    date.arr_pvp_date.map(function(pvp_date, key) {
                        article_finish.amount += pvp_date.arr_pvp.length;
                        total_amount_global += pvp_date.arr_pvp.length;
                        pvp_date.arr_pvp.map(function(pvp, key) {
                            article_finish.total += pvp;
                            total_global += pvp;
                        });
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
                            date_aux.arr_pvp_date.map(function(pvp_date, key) {
                                pvp_date.arr_pvp.map(function(pvp, key) {
                                    total_date += pvp;
                                });
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

        state.proposals.proposal_obj.array_dates = array_dates_prices;
        state.proposals.proposal_obj.is_change = true;
    },

    changeValueIsChangeArticle(state){
        state.proposals.proposal_obj.is_change = false;
    },

    //Generar propuesta
    generateBill(state, params){
        //Modificamos el objeto con los nuevos datos dados
        state.proposals.proposal_obj.products.map(function(products_obj, key_products_obj) {
            products_obj.articles.map(function(article_obj, key_article_obj) {
                params.map(function(products, key_products) {
                    products.article.map(function(article, key) {
                        article.dates.map(function(date, key) {
                            if(article_obj.article_obj.id == date.article.id){
                                article_obj.dates_prices.map(function(date_price_obj, key_date_price_obj) {
                                    date_price_obj.arr_pvp_date.map(function(arr_pvp_date_obj, key_arr_pvp_date) {
                                        date.date_pvp.map(function(date_pvp, key) {
                                            if(arr_pvp_date_obj.date == date_pvp.date){
                                                var out = false;
                                                if(!out){
                                                    date_pvp.pvp.map(function(pvp, key_pvp) {
                                                        state.proposals.proposal_obj.products[key_products_obj].articles[key_article_obj].dates_prices[key_date_price_obj].arr_pvp_date[key_arr_pvp_date].arr_pvp[key_pvp] = pvp;
                                                        //arr_pvp_obj = pvp;
                                                        out = true;
                                                    });
                                                }
                                            }
                                        });
                                    });
                                });
                            }
                        });
                    });
                });
            });
        });

        var array_articles = [];
        /*state.proposals.proposal_obj.products.map(function(products, key) {
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
        });*/
            
        state.proposals.proposal_obj.products.map(function(products, key) {
            products.articles.map(function(article_obj, key) {
                article_obj.dates_prices.map(function(dates_prices_obj, key) {
                    dates_prices_obj.arr_pvp_date.map(function(arr_pvp_date_obj, key) {
                        arr_pvp_date_obj.arr_pvp.map(function(arr_pvp_obj, key) {
                            var article_obj_aux = {
                                date: arr_pvp_date_obj.date,
                                article: article_obj,
                                id_product: products.product_obj.id,
                                amount: arr_pvp_obj
                            }
                            array_articles.push(article_obj_aux);
                        });
                    });
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
                amount = Number(article_obj.amount);
                total_bill += Number(article_obj.amount);
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
                                    amount += Number(article_obj.amount);
                                    total_bill += Number(article_obj.amount);
                                    array_finish_bill[last_key].amount = amount;
                                    is_break = true;

                                }else{
                                    amount = 0;
                                    date_aux =  article_obj.date;
                                    amount += article_obj.amount;
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
                                amount += Number(article_obj.amount);
                                total_bill += Number(article_obj.amount);
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
                    amount += article_obj.amount;
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

        /*array_articles.map(function(article_obj, key) {
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
        });*/
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
