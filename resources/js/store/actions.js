import http from "../axios";

const actions = {
    //Conultar información para el formulario de usuario
    async getInfoFormAddUser({ state }){
        try {
            const response = await http({
                url: "/admin/get_info_form_add_user",
            });

            state.config.users.array_positions = response.data.array_positions;
            state.config.users.array_roles = response.data.array_roles;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Añadir usuario
    async addUser({ state }, params){
        try {
            const response = await http({
                url: "/admin/add_user",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'add_user';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar informacion de un usuario
    async getInfoUser({ state }, id){
        try {
            const response = await http({
                url: "/admin/get_info_user/" + id,
            });
            
            state.config.users.user_obj = response.data.user;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Actualizar usuario 
    async updateUser({ state }, params){
        try {
            const response = await http({
                url: "/admin/update_user",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'update_user';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Eliminar un usuario
    async deleteUser({ state }, id){
        try {
            const response = await http({
                url: "/admin/delete_user/" + id,
            });
            
            state.errors.type_error = 'delete_user';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Conultar información para el formulario de calendarios
    async getInfoFormAddCalendar({ state }){
        try {
            const response = await http({
                url: "/admin/get_info_form_add_calendar",
            });

            state.config.calendars.array_calendars = response.data.array_calendars;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Añadir usuario
    async addCalendar({ state }, params){
        try {
            const response = await http({
                url: "/admin/add_calendar",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'add_calendar';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Eliminar un calendario
    async deleteCalendar({ state }, id){
        try {
            const response = await http({
                url: "/admin/delete_calendar/" + id,
            });
            
            state.errors.type_error = 'delete_calendar';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },
    
    //Consultar informacion de un calendario
    async getInfoCalendar({ state }, id){
        try {
            const response = await http({
                url: "/admin/get_info_calendar/" + id,
            });
            
            state.config.calendars.calendar_obj = response.data.calendar;

        } catch (error) {
            console.error(error);

            return error;
        }
    },


    //Actualizar usuario 
    async updateCalendar({ state }, params){
        try {
            const response = await http({
                url: "/admin/update_calendar",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'update_calendar';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar calendarios para exportar 
    async listCalendarsToExport({ state }, params){
        try {
            const response = await http({
                url: "/admin/list_calendars_to_export",
                params: {
                    select_calendar_filter: params.select_calendar_filter
                },
                method: 'post'
            });

            state.config.calendars.html_calendar = response.data.array_calendars;

        } catch (error) {
            console.error(error);

            return error;
        }
    },
    
    //Descargar csv del calendarios
    async downloadListCalendarsCsv({ state }, params){
        try {
            const response = await http({
                url: "/admin/download_list_calendars_csv",
                params: params,
                method: 'get'
            });

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar areas
    async getAreas({ state }){
        try {
            const response = await http({
                url: "/admin/get_areas",
                method: 'get'
            });

            state.config.articles.form.array_areas = response.data.array_areas;
            state.config.articles.form.array_sectors = null;
            state.config.articles.form.array_brands = null;
            state.config.articles.form.array_products = null;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar sectores
    async getSectors({ state }, params){
        try {
            var select_articles_areas = 0;
            if(params.select_articles_areas != null || params.search_articles != undefined){
                select_articles_areas = params.select_articles_areas;
            }
            const response = await http({
                url: "/admin/get_sectors/" + select_articles_areas,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_sectors = response.data.array_sectors;
                state.config.articles.filter.array_brands = null;
                state.config.articles.filter.array_products = null;  

            }else {
                if(params.select_articles_areas != 0 && params.select_articles_areas != ""){
                    state.config.articles.form.array_sectors = response.data.array_sectors;
                    state.config.articles.form.array_brands = null;
                    state.config.articles.form.array_products = null;  
                    state.config.articles.form.array_articles = null;  
    
                }else{
                    state.config.articles.form.array_sectors = null;
                    state.config.articles.form.array_brands = null;
                    state.config.articles.form.array_products = null;  
                    state.config.articles.form.array_articles = null;  
                }
            }
            

        } catch (error) {
            console.error(error);

            return error;
        }
    },
    
    //Consultar marcas
    async getBrands({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_brands/" + params.select_articles_sectors,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_brands = response.data.array_brands;
                state.config.articles.filter.array_products = null;
            }else{
                state.config.articles.form.array_brands = response.data.array_brands;
                state.config.articles.form.array_products = null;
                state.config.articles.form.array_articles = null;  
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar productos
    async getProducts({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_products/" + params.select_articles_brands,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_products = response.data.array_products;
            }else{
                state.config.articles.form.array_articles = null;  
                state.config.articles.form.array_products = response.data.array_products;
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar artículos
    async getArticles({ state }, params){
        try {
            
            state.config.articles.form.array_articles = null;  

            const response = await http({
                url: "/admin/get_articles/" + params.select_articles_products,
                method: 'get'
            });

            state.config.articles.form.array_articles = response.data.array_articles;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Añadir artículo
    async addArticle({ state }, params){
        try {
            const response = await http({
                url: "/admin/add_article",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'add_article';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar informacion de un artículo
    async getInfoArticle({ state }, id){
        try {
            const response = await http({
                url: "/admin/get_info_article/" + id,
            });
            
            state.config.articles.article_obj = response.data.article;
            state.config.articles.form.array_areas = response.data.array_areas;
            state.config.articles.form.array_sectors = response.data.array_sectors;
            state.config.articles.form.array_brands = response.data.array_brands;
            state.config.articles.form.array_products = response.data.array_products;

        } catch (error) {
            console.error(error);

            return error;
        }
    },
    
    //Eliminar un artículo
    async deleteArticle({ state }, id){
        try {
            const response = await http({
                url: "/admin/delete_article/" + id,
            });
            
            state.errors.type_error = 'delete_article';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Actualizar articulo 
    async updateArticle({ state }, params){
        try {
            const response = await http({
                url: "/admin/update_article",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'update_article';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar artículos para exportar 
    async listArticlesToExport({ state }, params){
        try {
            const response = await http({
                url: "/admin/list_articles_to_export",
                params: {
                    select_articles_filter_sectors: params.select_articles_filter_sectors,
                    select_articles_filter_brands: params.select_articles_filter_brands,
                    select_articles_filter_products: params.select_articles_filter_products,
                    search_articles: params.search_articles
                },
                method: 'post'
            });

            state.config.articles.html_articles = response.data.array_articles;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar usuarios para el select 
    async getUsers({ state }, type){
        try {
            const response = await http({
                url: "/admin/get_users",
                method: 'get'
            });

            if(type == 1){
                state.proposals.array_users = response.data.array_users;
            }else{
                state.orders.array_users = response.data.array_users;
            }
            

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar empresas para el select 
    async getCompanies({ state }, type){
        try {
            const response = await http({
                url: "/admin/get_companies",
                method: 'get'
            });

            if(type == 1){
                state.proposals.array_companies = response.data.array_companies;
                state.proposals.user_obj = response.data.user;
            }else{
                state.orders.array_companies = response.data.array_companies;
                state.orders.user_obj = response.data.user;
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Guardar propuesta
    async saveProposal({ state }, params){
        try {
            const response = await http({
                url: "/admin/save_generate_proposal",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'save_proposal';
            state.errors.code = response.data.code;
            state.errors.msg = response.data.pdf_file;

        } catch (error) {
            console.error(error);

            return error;
        }
    },
    
    //Mostrar información de la propuesta
    async getInfoProposal({ state }, id){
        try {
            const response = await http({
                url: "/admin/get_info_proposal/"+id,
                method: 'get'
            });

            //Rellenar objetos para el store y mostrar la información de las propuestas
            createObjectsStore({ state }, response, 1);

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Actualizar propuesta
    async updateProposal({ state }, params){
        try {
            const response = await http({
                url: "/admin/update_proposal",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'update_proposal';
            state.errors.code = response.data.code;
            state.errors.msg = response.data.pdf_file;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Eliminar propuesta
    async deleteProposal({ state }, id){
        try {
            const response = await http({
                url: "/admin/delete_proposal/"+id,
                method: 'get'
            });

            state.errors.type_error = 'delete_proposal';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Mostrar información de la orden
    async getInfoOrder({ state }, id){
        try {
            const response = await http({
                url: "/admin/get_info_proposal/"+id,
                method: 'get'
            });

            //Rellenar objetos para el store y mostrar la información de las propuestas
            createObjectsStore({ state }, response, 2);

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Listar propuestas para exportar 
    async listProposalsToExport({ state }, params){
        try {
            const response = await http({
                url: "/admin/list_proposals_to_export",
                params: {
                    select_calendar_filter: params.select_calendar_filter
                },
                method: 'post'
            });

            state.proposals.html_proposal_list = response.data.array_proposals;

        } catch (error) {
            console.error(error);

            return error;
        }
    },
}

//Rellenar objetos para el store y mostrar la información de las propuestas u ordenes
function createObjectsStore({ state }, response, type){
    var custom_state = state.proposals;
    if(type == 2){
        custom_state = state.orders;
    }
    var array_services = response.data.array_services;
    custom_state.proposal_obj.products.articles = [];
    custom_state.proposal_obj.products.dates_prices_aux = [];
    custom_state.bill_obj.array_bills = [];
    var array_dates_aux = [];
    var array_products = [];
    var proposal = response.data.proposal
    array_services.forEach(function callback(service, index, array) {
        array_dates_aux.push(service.date);

        if(array_products.length == 0){
            var article = {
                amount: 1,
                article_obj: service.article,
                dates: [service.date],
                dates_prices: [{
                    arr_pvp_date: [{
                        date: service.date,
                        arr_pvp: [service.pvp]
                    }],
                    date: changeFormatDate(service.date)
                }],
                total: service.pvp,
                sector_obj: proposal.sector
            }
            array_products.push({
                id_product: service.article.id_product,
                articles: [article],
                articles_aux: [article],
                product_obj: service.product,
            });

        }else{
            var exist_1 = false;
            array_products.forEach(function callback(product, index, array) {
                if(product.id_product == service.article.id_product){
                    exist_1 = true;
                    var exist_2 = false;
                    product.articles.forEach(function callback(article, index, array) {
                        if(article.article_obj.id == service.id_article){
                            exist_2 = true;
                            article.amount += 1;
                            article.dates.push(service.date);
                            article.total = article.total + service.pvp;
                            var exist_3 = false;
                            article.dates_prices.forEach(function callback(date_price, index, array) {
                                if(date_price.date == changeFormatDate(service.date)){
                                    exist_3 = true;
                                    var exist_4 = false;
                                    date_price.arr_pvp_date.forEach(function callback(pvp_date, index, array) {
                                        if(pvp_date.date == service.date){
                                            pvp_date.arr_pvp.push(service.pvp);
                                            exist_4 = true;
                                        }
                                    });
                                    if(!exist_4){
                                        var pvp_date = {
                                            date: service.date,
                                            arr_pvp: [service.pvp]
                                        };
                                        date_price.arr_pvp_date.push(pvp_date);
                                    }
                                }
                                
                            });
                            if(!exist_3){
                                var date_price = {
                                    date: changeFormatDate(service.date),
                                    arr_pvp_date: [{
                                        date: service.date,
                                        arr_pvp: [service.pvp]
                                    }]
                                }
                                article.dates_prices.push(date_price);
                            }
                        }
                    });

                    if(!exist_2){
                        var article = {
                            amount: 1,
                            article_obj: service.article,
                            dates: [service.date],
                            dates_prices: [{
                                arr_pvp_date: [{
                                    date: service.date,
                                    arr_pvp: [service.pvp]
                                }],
                                date: changeFormatDate(service.date)
                            }],
                            total: service.pvp,
                            sector_obj: proposal.sector
                        }
                        product.articles.push(article);
                    }
                }
            });

            if(!exist_1){
                var article = {
                    amount: 1,
                    article_obj: service.article,
                    dates: [service.date],
                    dates_prices: [{
                        arr_pvp_date: [{
                            date: service.date,
                            arr_pvp: [service.pvp]
                        }],
                        date: changeFormatDate(service.date)
                    }],
                    total: service.pvp,
                    sector_obj: proposal.sector
                }
                array_products.push({
                    id_product: service.article.id_product,
                    articles: [article],
                    product_obj: service.product
                });
            }
        }
        
    });
    custom_state.proposal_obj.products = array_products;

    //Consultamos los totales
    var total_amount_global = 0;
    var total_individual_pvp = 0;
    var total_global = 0;
    custom_state.proposal_obj.products.map(function(product, key) {
        product.articles.map(function(article, key) {
            total_individual_pvp += article.article_obj.pvp;
            article.dates_prices.map(function(date_price, key) {
                date_price.arr_pvp_date.map(function(pvp_date, key) {
                    total_amount_global += pvp_date.arr_pvp.length;
                    pvp_date.arr_pvp.map(function(pvp, key) {
                        total_global += Number(pvp);
                    });
                });
            });
        });
    });
    custom_state.proposal_obj.total_individual_pvp = total_individual_pvp;
    custom_state.proposal_obj.total_amount_global = total_amount_global;
    custom_state.proposal_obj.total_global_normal = total_global;
    custom_state.proposal_obj.total_global = total_global;

    //Ordenamos las fechas de forma ascendente
    array_dates_aux = array_dates_aux.sort(function(a,b){
        var b_aux = Date.parse(new Date(changeFormatDate2(b)));
        var a_aux = Date.parse(new Date(changeFormatDate2(a)));
        return a_aux - b_aux;
    });

    var array_dates = [];
    //Modificamos el formato de las fechas para las columnas
    array_dates_aux.map(function(date, key) {
        var new_date = changeFormatDate(date);
        if(!array_dates.includes(new_date)){
            array_dates.push(new_date);
        }
    });

    //Cargamos en el array de fechas para las columnas los totales de cada mes
    var array_dates_prices = [];
    array_dates.map(function(date, key) {
        var total_date = 0;
        custom_state.proposal_obj.products.map(function(articles_obj, key) {
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

    //Recogemos datos de la propuesta
    var proposal = response.data.proposal
    var proposal_submission_settings = {
        id: proposal.id,
        commercial_name: proposal.commercial_name,
        language: proposal.language,
        type_proyect: proposal.type_proyect,
        name_proyect: proposal.name_proyect,
        date_proyect: proposal.date_proyect,
        objetives: proposal.objetives,
        proposal: proposal.proposal,
        actions: proposal.actions,
        observations: proposal.observations,
        show_discounts: proposal.show_discounts,
        show_inserts: proposal.show_inserts,
        show_invoices: proposal.show_invoices,
        show_pvp: proposal.show_pvp,
        sales_possibilities: proposal.sales_possibilities,
        id_proposal_custom: proposal.id_proposal_custom,
        id_proposal_custom_aux: proposal.id_proposal_custom_aux
    }
    
    //Guardamos con un nuevo formato para las facturas los articulos
    var array_articles = [];
    custom_state.proposal_obj.products.map(function(products, key) {
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
    custom_state.bill_obj.articles = array_articles;

    //Creamos el objeto factura
    var array_bills = response.data.proposal_bills;

    var count_bill = 0;
    array_articles.map(function(article_obj, key) {
        if(key == 0){
            amount = Number(article_obj.amount);
            total_bill += Number(article_obj.amount);
            var bill_month = {
                date: date_aux,
                amount: amount,
                article: article_obj,
                select_way_to_pay: array_bills[count_bill].way_to_pay,
                select_expiration: array_bills[count_bill].expiration,
                observations: array_bills[count_bill].observations,
                order_number: array_bills[count_bill].num_order,
                internal_observations: array_bills[count_bill].internal_observations,
            }

            count_bill ++;
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
                            }
                        }
                    }
                });

                if(!is_break){
                    amount = 0;
                    date_aux = article_obj.date;
                    amount += Number(article_obj.amount);
                    total_bill += Number(article_obj.amount);
                    var bill_month = {
                        date: date_aux,
                        amount: amount,
                        article: article_obj,
                        select_way_to_pay: array_bills[count_bill].way_to_pay,
                        select_expiration: array_bills[count_bill].expiration,
                        observations: array_bills[count_bill].observations,
                        order_number: array_bills[count_bill].num_order,
                        internal_observations: array_bills[count_bill].internal_observations,
                    }
                    array_finish_bill.push(bill_month);
                    count_bill++;
                    last_key = (array_finish_bill.length - 1);
                }

            }else{
                amount = 0;
                date_aux =  article_obj.date;
                amount += Number(article_obj.amount);
                total_bill += Number(article_obj.amount);
                var bill_month = {
                    date: date_aux,
                    amount: amount,
                    article: article_obj,
                    select_way_to_pay: array_bills[count_bill].way_to_pay,
                    select_expiration: array_bills[count_bill].expiration,
                    observations: array_bills[count_bill].observations,
                    order_number: array_bills[count_bill].num_order,
                    internal_observations: array_bills[count_bill].internal_observations,
                }
                count_bill++;
                array_finish_bill.push(bill_month);
                last_key = (array_finish_bill.length - 1);
            }
        }
    });

    custom_state.bill_obj.array_bills = array_finish_bill;
    custom_state.bill_obj.total_bill = total_bill;

    //Guardamos datos
    custom_state.proposal_bd_obj = proposal_submission_settings;
    custom_state.proposal_obj.array_dates = array_dates_prices;
    if(type == 1){
        custom_state.status_view = 3;
    }else{
        custom_state.status_view = 2;
    }
    state.errors.type_error = 'get_info_proposal';
    state.errors.code = response.data.code;
    custom_state.is_change_get_info = 1;
    custom_state.id_company = response.data.proposal.id_company;
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

export default actions;
