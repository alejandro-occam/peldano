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
    async getUsers({ state }){
        try {
            const response = await http({
                url: "/admin/get_users",
                method: 'get'
            });

            state.proposals.array_users = response.data.array_users;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar empresas para el select 
    async getCompanies({ state }){
        try {
            const response = await http({
                url: "/admin/get_companies",
                method: 'get'
            });

            state.proposals.array_companies = response.data.array_companies;
            state.proposals.user_obj = response.data.user;

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

        } catch (error) {
            console.error(error);

            return error;
        }
    },
    
}

export default actions;
