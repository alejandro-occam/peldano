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
            state.proposals.user_obj = response.data.user;

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

    //Consultar departamentos
    async getDepartments({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_departments",
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_departments = response.data.array_departments;
                state.config.articles.filter.array_sections = null;
                state.config.articles.filter.array_channels = null;
                state.config.articles.filter.array_projects = null;
                state.config.articles.filter.array_chapters = null;
                state.config.articles.filter.array_batchs = null;
                state.config.articles.filter.array_articles = null;

            }else {
                if(params.select_articles_areas != 0 && params.select_articles_areas != ""){
                    state.config.articles.form.array_departments = response.data.array_departments;
                    state.config.articles.form.array_sections = null;
                    state.config.articles.form.array_channels = null;
                    state.config.articles.form.array_projects = null;
                    state.config.articles.form.array_chapters = null;
                    state.config.articles.form.array_batchs = null;
                    state.config.articles.form.array_articles = null;
    
                }else{
                    state.config.articles.form.array_departments = null;
                    state.config.articles.form.array_sections = null;
                    state.config.articles.form.array_channels = null;
                    state.config.articles.form.array_projects = null;
                    state.config.articles.form.array_chapters = null;
                    state.config.articles.form.array_batchs = null;
                    state.config.articles.form.array_articles = null;
                }
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar secciones
    async getSections({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_sections/" + params.select_articles_department,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_sections = response.data.array_sections;
                state.config.articles.filter.array_channels = null;
                state.config.articles.filter.array_projects = null;
                state.config.articles.filter.array_chapters = null;
                state.config.articles.filter.array_batchs = null;
                state.config.articles.filter.array_articles = null;

            }else {
                state.config.articles.form.array_sections = response.data.array_sections;
                state.config.articles.form.array_channels = null;
                state.config.articles.form.array_projects = null;
                state.config.articles.form.array_chapters = null;
                state.config.articles.form.array_batchs = null;
                state.config.articles.form.array_articles = null;
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar canales
    async getChannels({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_channels/" + params.select_articles_section,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_channels = response.data.array_channels;
                state.config.articles.filter.array_projects = null;
                state.config.articles.filter.array_chapters = null;
                state.config.articles.filter.array_batchs = null;
                state.config.articles.filter.array_articles = null;

            }else{
                state.config.articles.form.array_channels = response.data.array_channels;
                state.config.articles.form.array_projects = null;
                state.config.articles.form.array_chapters = null;
                state.config.articles.form.array_batchs = null;
                state.config.articles.form.array_articles = null;
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar proyectos
    async getProjects({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_projects/" + params.select_articles_channel,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_projects = response.data.array_projects;
                state.config.articles.filter.array_chapters = null;
                state.config.articles.filter.array_batchs = null;
                state.config.articles.filter.array_articles = null;

            }else{
                state.config.articles.form.array_projects = response.data.array_projects;
                state.config.articles.form.array_chapters = null;
                state.config.articles.form.array_batchs = null;
                state.config.articles.form.array_articles = null;
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar capítulos
    async getChapters({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_chapters/" + params.select_articles_project,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_chapters = response.data.array_chapters;
                state.config.articles.filter.array_batchs = null;
                state.config.articles.filter.array_articles = null;

            }else{
                state.config.articles.form.array_chapters = response.data.array_chapters;
                state.config.articles.form.array_batchs = null;
                state.config.articles.form.array_articles = null;
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Consultar lotes
    async getBatchs({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_batchs/" + params.select_articles_chapter,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_batchs = response.data.array_batchs;
                state.config.articles.filter.array_articles = null;

            }else{
                state.config.articles.form.array_batchs = response.data.array_batchs;
                state.config.articles.form.array_articles = null;
            }

        } catch (error) {
            console.error(error);

            return error;
        }
    },

     //Añadir lote
     async addBatch({ state }, params){
        try {
            const response = await http({
                url: "/admin/add_batch",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'add_batch';
            state.errors.code = response.data.code;

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
                url: "/admin/get_articles/" + params.select_articles_batch,
                method: 'get'
            });

            if(params.type == 1){
                state.config.articles.filter.array_articles = response.data.array_articles;
            }else{
                state.config.articles.form.array_articles = response.data.array_articles;
            }
           

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
            state.config.articles.form.array_departments = response.data.array_departments;
            state.config.articles.form.array_sections = response.data.array_sections;
            state.config.articles.form.array_channels = response.data.array_channels;
            state.config.articles.form.array_projects = response.data.array_projects;
            state.config.articles.form.array_chapters = response.data.array_chapters;
            state.config.articles.form.array_batchs = response.data.array_batchs;

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

    //Mostrar infromación del usuario
    async getUser({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_user",
                method: 'get'
            });

            if(params.type == 1){
                state.proposals.user_obj = response.data.user;
                var consultant = {
                    id_consultant: response.data.user.id,
                    name: response.data.user.name + " "  + response.data.user.surname,
                    percentage: 100
                }
                if(params.type_action == 0){
                    state.proposals.proposal_obj.array_consultants.push(consultant);
                }
                
            }else{
                state.orders.user_obj = response.data.user;
                var consultant = {
                    id_consultant: response.data.user.id,
                    name: response.data.user.name + " "  + response.data.user.surname,
                    percentage: 100
                }
                if(params.type_action == 0){
                    state.orders.proposal_obj.array_consultants.push(consultant);
                }
                
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

    //Validar propuesta
    async validateProposal({ state }, id){
        try {
            const response = await http({
                url: "/admin/validate_proposal/"+id,
                method: 'get'
            });

            state.errors.type_error = 'validate_proposal';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Mostrar información de la orden
    async getInfoOrder({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_info_order/"+params.id,
                method: 'get'
            });

            //Rellenar objetos para el store y mostrar la información de las propuestas
            state.orders.user_control = response.data.user_control;
            if(params.type == 1){
                createObjectsStoreOrders({ state }, response, 2);
            }else{
                createObjectsStoreInfo({ state }, response), 2;
            }
            

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Eliminar orden
    async deleteOrder({ state }, id){
        try {
            const response = await http({
                url: "/admin/delete_order/"+id,
                method: 'get'
            });

            state.errors.type_error = 'delete_order';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Actualizar propuesta
    async updateOrder({ state }, params){
        try {
            const response = await http({
                url: "/admin/update_order",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'update_order';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Copiar orden
    async copyOrder({ state }, id){
        try {
            const response = await http({
                url: "/admin/copy_order/"+id,
                method: 'get'
            });

            state.errors.type_error = 'copy_order';
            state.errors.code = response.data.code;

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
                params: params,
                method: 'post'
            });

            state.proposals.html_proposal_list = response.data.array_proposals;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar ordenes para exportar 
    async listOrdersToExport({ state }, params){
        try {
            const response = await http({
                url: "/admin/list_orders_to_export",
                params: params,
                method: 'post'
            });

            state.orders.html_orders_list = response.data.array_orders;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Crear orden
    async createOrder({ state }, params){
        try {
            const response = await http({
                url: "/admin/create_order",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'create_order';
            state.errors.code = response.data.code;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar ordenes por canal
    async reportListByChannel({ state }, params){
        try {
            const response = await http({
                url: "/admin/report_list_by_channel",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'report_list_by_channel';
            state.errors.code = response.data.code;
            state.reports.array_dates = response.data.array_dates;
            state.reports.array_bills_orders = response.data.array_bills_orders_custom;
            state.reports.percent_old = response.data.percent_old;
            state.reports.percent_new = response.data.percent_new;
            state.reports.period_new = response.data.period_new;
            state.reports.period_old = response.data.period_old;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Descargar csv ordenes por canal
    async downloadReportListByChannel({ state }, params){
        try {
            const response = await http({
                url: "/admin/download_reports_list_by_channel_csv",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'download_reports_list_by_channel_csv';
            /*state.errors.code = response.data.code;
            state.reports.array_dates = response.data.array_dates;
            state.reports.array_bills_orders = response.data.array_bills_orders_custom;
            state.reports.percent_old = response.data.percent_old;
            state.reports.percent_new = response.data.percent_new;
            state.reports.period_new = response.data.period_new;
            state.reports.period_old = response.data.period_old;*/

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar ordenes firmadas y ventas
    async reportListSalesOrdersSigned({ state }, params){
        try {
            const response = await http({
                url: "/admin/report_sales_orders_signed",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'report_sales_orders_signed';
            state.errors.code = response.data.code;
            state.reports.array_dates = response.data.array_dates;
            state.reports.array_bills_orders = response.data.array_bills_orders_custom;
            state.reports.percent_old = response.data.percent_old;
            state.reports.percent_new = response.data.percent_new;
            state.reports.period_new = response.data.period_new;
            state.reports.period_old = response.data.period_old;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listar ordenes firmadas y ventas
    async reportListBilled({ state }, params){
        try {
            const response = await http({
                url: "/admin/report_billed",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'report_billed';
            state.errors.code = response.data.code;
            state.reports.array_dates = response.data.array_dates;
            state.reports.array_bills_orders = response.data.array_bills_orders_custom;
            state.reports.percent_old = response.data.percent_old;
            state.reports.percent_new = response.data.percent_new;
            state.reports.period_new = response.data.period_new;
            state.reports.period_old = response.data.period_old;

        } catch (error) {
            console.error(error);

            return error;
        }
    },

    //Listado de facturas impagadas
    async reportUnpaidInvoices({ state }, params){
        try {
            const response = await http({
                url: "/admin/report_unpaid_invoices",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'report_unpaid_invoices';
            state.errors.code = response.data.code;
            state.reports.array_bills_orders = response.data.array_bill_orders;
            state.reports.total_amount = response.data.total_amount;

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Listado de facturas insercciones
    async reportInsertions({ state }, params){
        try {
            const response = await http({
                url: "/admin/report_intertions",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'report_intertions';
            state.errors.code = response.data.code;
            state.reports.array_bills_orders = response.data.array_articles;
            state.reports.total_amount = response.data.total_amount;

        } catch (error) {
            console.error(error);
            return error;
        }
    },
    
    //Abonar pago
    async payInvoice({ state }, id){
        try {
            const response = await http({
                url: "/admin/pay_invoice/" + id,
                params: '',
                method: 'get'
            });

            state.errors.type_error = 'pay_invoice';
            state.errors.code = response.data.code;

            //Cambiamos el estado de la factura
            state.orders.bill_obj.array_bills.map(function(bill, key) {
                if(bill.id == id){
                    bill.will_update = false;
                    state.orders.bill_obj.total_bill -= bill.amount;
                }
            });
            

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Listar facturas para la validación
    async listBillsValidation({ state }, params){
        try {
            const response = await http({
                url: "/admin/list_bill_orders",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'list_bill_orders';
            state.errors.code = response.data.code;
            state.invoice_validations.array_bill_orders = response.data.array_bill_orders;

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Validar facturas
    async validateBill({ state }, params){
        try {
            const response = await http({
                url: "/admin/validate_bill",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'validate_bill';
            state.errors.code = response.data.code;
            //Cambiamos el estado de la factura
            state.invoice_validations.array_bill_orders.map(function(bill, key) {
                if(bill.id == params.id_bill){
                    bill.status_validate = 1;
                }
            });

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Consultar calendarios para las suscripciones
    async getCalendarsSuscriptions({ state }){
        try {
            const response = await http({
                url: "/admin/get_calendars_suscriptions",
                params: '',
                method: 'get'
            });

            state.errors.type_error = 'get_calendars_suscriptions';
            state.errors.code = response.data.code;
            state.suscriptions.array_calendars = response.data.array_calendars;
            

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Consultar calendarios de revistas
    async getCalendarsMagazines({ state }, id){
        try {
            const response = await http({
                url: "/admin/get_calendars_magazines/" + id,
                params: '',
                method: 'get'
            });

            state.errors.type_error = 'get_calendars_magazines';
            state.errors.code = response.data.code;
            state.suscriptions.array_calendars_magazines = response.data.array_calendars_magazines;
            

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Listar artículos para las suscripciones 
    async getArticlesSuscriptions({ state }, id){
        try {
            const response = await http({
                url: "/admin/list_articles_suscriptions/"+id,
                params: '',
                method: 'get'
            });

            state.errors.type_error = 'list_articles_suscriptions';
            state.errors.code = response.data.code;
            state.suscriptions.array_articles = response.data.array_articles;
            

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Añadir suscripción
    async addSusctiption({ state }, params){
        try {
            const response = await http({
                url: "/admin/add_suscription",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'add_suscription';
            state.errors.code = response.data.code;
            

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Actualizar suscripción 
    async updateSusctiptions({ state }, params){
        try {
            const response = await http({
                url: "/admin/update_suscription",
                params: params,
                method: 'post'
            });

            state.errors.type_error = 'update_suscription';
            state.errors.code = response.data.code;
            

        } catch (error) {
            console.error(error);
            return error;
        }
    },

    //Eliminar suscripción
    async deleteSuscription({ state }, id){
        try {
            const response = await http({
                url: "/admin/delete_suscription/"+id,
                params: '',
                method: 'get'
            });

            state.errors.type_error = 'delete_suscription';
            state.errors.code = response.data.code;            

        } catch (error) {
            console.error(error);
            return error;
        }
    }
}

//Rellenar objetos para el store y mostrar la información de las propuestas u ordenes
function createObjectsStore({ state }, response, type){
    console.log(response.data);
    var custom_state = state.proposals;
    if(type == 2){
        custom_state = state.orders;
    }

    if(response.data.proposal.is_custom){
        custom_state.num_custom_invoices = Number(response.data.proposal_bills.length);
    }

    var array_services = response.data.array_services;
    custom_state.proposal_obj.chapters.articles = [];
    custom_state.proposal_obj.chapters.dates_prices_aux = [];
    custom_state.bill_obj.array_bills = [];
    var array_dates_aux = [];
    var array_chapters = [];
    var proposal = response.data.proposal;

    if(array_services != undefined){
        array_services.forEach(function callback(service, index, array) {
            array_dates_aux.push(service.date);

            if(array_chapters.length == 0){
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
                    chapter_obj: service.chapter,
                    department_obj: proposal.department_obj
                }
                array_chapters.push({
                    id_chapter: service.chapter.id,
                    articles: [article],
                    articles_aux: [article],
                    chapter_obj: service.chapter
                });
    
            }else{
                var exist_1 = false;
                array_chapters.forEach(function callback(chapter, index, array) {
                    if(chapter.id_chapter == service.chapter.id){
                        exist_1 = true;
                        var exist_2 = false;
                        chapter.articles.forEach(function callback(article, index, array) {
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
                                chapter_obj: service.chapter,
                                department_obj: proposal.department_obj
                            }
                            chapter.articles.push(article);
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
                        chapter_obj: service.chapter,
                        department_obj: proposal.department_obj
                    }
                    array_chapters.push({
                        id_chapter: service.chapter.id,
                        articles: [article],
                        articles_aux: [article],
                        chapter_obj: service.chapter
                    });
                }
            }
            
        });
        custom_state.proposal_obj.chapters = array_chapters;
    }

    //Consultamos los totales
    var total_amount_global = 0;
    var total_individual_pvp = 0;
    var total_global = 0;
    var total_global_default = 0;
    custom_state.proposal_obj.chapters.map(function(chapter, key) {
        chapter.articles.map(function(article, key) {
            total_individual_pvp += article.article_obj.pvp;
            article.dates_prices.map(function(date_price, key) {
                date_price.arr_pvp_date.map(function(pvp_date, key) {
                    total_amount_global += pvp_date.arr_pvp.length;
                    pvp_date.arr_pvp.map(function(pvp, key) {
                        total_global += Number(pvp);
                        total_global_default += Number(article.article_obj.pvp);
                    });
                });
            });
        });
    });
    custom_state.proposal_obj.total_individual_pvp = total_individual_pvp;
    custom_state.proposal_obj.total_amount_global = total_amount_global;
    custom_state.proposal_obj.total_global_normal = total_global_default;
    custom_state.proposal_obj.total_global = total_global;
    
    custom_state.proposal_obj.array_consultants = response.data.array_consultants;

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
        custom_state.proposal_obj.chapters.map(function(articles_obj, key) {
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
        id_proposal_custom_aux: proposal.id_proposal_custom_aux,
        discount: proposal.discount,
        status: proposal.status,
        advertiser: proposal.advertiser,
        type_proposal: proposal.type_proposal
    }
    
    //Guardamos con un nuevo formato para las facturas los articulos
    var array_articles = [];
    custom_state.proposal_obj.chapters.map(function(chapters, key) {
        chapters.articles.map(function(article_obj, key) {
            article_obj.dates_prices.map(function(dates_prices_obj, key) {
                dates_prices_obj.arr_pvp_date.map(function(arr_pvp_date_obj, key) {
                    arr_pvp_date_obj.arr_pvp.map(function(arr_pvp_obj, key) {
                        var article_obj_aux = {
                            date: arr_pvp_date_obj.date,
                            article: article_obj,
                            id_chapter: chapters.chapter_obj.id,
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

    var date_aux = '';
    if(!response.data.proposal.is_custom){
        if(array_articles.length > 0){
            date_aux = array_articles[0].date;
        }
    }
    var amount = 0;
    var array_finish_bill = [];
    var last_key = 0;
    var total_bill = 0;
    custom_state.bill_obj.articles = array_articles;

    //Creamos el objeto factura
    var array_bills = response.data.proposal_bills;

    var count_bill = 0;
    if(!response.data.proposal.is_custom){
        array_articles.map(function(article_obj, key) {
            if(key == 0){
                if(response.data.proposal.is_custom){
                    amount = array_bills[count_bill].amount;
                    date_aux = array_bills[count_bill].date;
                }else{
                    amount = Number(array_bills[count_bill].amount);
                }
                
                total_bill += Number(array_bills[count_bill].amount);
                var bill_month = {
                    id: array_bills[count_bill].id,
                    //date: date_aux,
                    date: array_bills[count_bill].date,
                    amount: amount,
                    article: article_obj,
                    select_way_to_pay: array_bills[count_bill].way_to_pay,
                    select_expiration: array_bills[count_bill].expiration,
                    observations: array_bills[count_bill].observations,
                    order_number: array_bills[count_bill].num_order,
                    internal_observations: array_bills[count_bill].internal_observations,
                    will_update: array_bills[count_bill].will_update,
                    status_validate: array_bills[count_bill].status_validate,
                }

                count_bill ++;
                array_finish_bill.push(bill_month);

            }else{
                if(!response.data.proposal.is_custom){
                    if(date_aux == article_obj.date){
                        var is_break = false;
                        array_finish_bill.map(function(bill_obj, key) {
                            if(!is_break){
                                if(bill_obj.date == article_obj.date){
                                    if(bill_obj.article.id_chapter == article_obj.id_chapter){
                                        amount += Number(array_bills[count_bill].amount);
                                        total_bill += Number(array_bills[count_bill].amount);
                                        array_finish_bill[last_key].amount = amount;
                                        is_break = true;
                                    }
                                }
                            }
                        });

                        if(!is_break){
                            amount = 0;
                            date_aux = article_obj.date;
                            amount += Number(array_bills[count_bill].amount);
                            total_bill += Number(array_bills[count_bill].amount);
                            var bill_month = {
                                id: array_bills[count_bill].id,
                                //date: date_aux,
                                date: array_bills[count_bill].date,
                                amount: amount,
                                article: article_obj,
                                select_way_to_pay: array_bills[count_bill].way_to_pay,
                                select_expiration: array_bills[count_bill].expiration,
                                observations: array_bills[count_bill].observations,
                                order_number: array_bills[count_bill].num_order,
                                internal_observations: array_bills[count_bill].internal_observations,
                                will_update: array_bills[count_bill].will_update,
                                status_validate: array_bills[count_bill].status_validate,
                            }
                            array_finish_bill.push(bill_month);
                            count_bill++;
                            last_key = (array_finish_bill.length - 1);
                        }

                    }else{
                        amount = 0;
                        date_aux =  article_obj.date;
                        amount += Number(array_bills[count_bill].amount);
                        total_bill += Number(array_bills[count_bill].amount);
                        var bill_month = {
                            id: array_bills[count_bill].id,
                            //date: date_aux,
                            date: array_bills[count_bill].date,
                            amount: amount,
                            article: article_obj,
                            select_way_to_pay: array_bills[count_bill].way_to_pay,
                            select_expiration: array_bills[count_bill].expiration,
                            observations: array_bills[count_bill].observations,
                            order_number: array_bills[count_bill].num_order,
                            internal_observations: array_bills[count_bill].internal_observations,
                            will_update: array_bills[count_bill].will_update,
                            status_validate: array_bills[count_bill].status_validate,
                        }
                        count_bill++;
                        array_finish_bill.push(bill_month);
                        last_key = (array_finish_bill.length - 1);
                    }

                }else{
                    amount = array_bills[count_bill].amount;
                    total_bill += Number(array_bills[count_bill].amount);
                    var bill_month = {
                        id: array_bills[count_bill].id,
                        //date: array_bills[count_bill].date,
                        date: array_bills[count_bill].date,
                        amount: amount,
                        article: article_obj,
                        select_way_to_pay: array_bills[count_bill].way_to_pay,
                        select_expiration: array_bills[count_bill].expiration,
                        observations: array_bills[count_bill].observations,
                        order_number: array_bills[count_bill].num_order,
                        internal_observations: array_bills[count_bill].internal_observations,
                        will_update: array_bills[count_bill].will_update,
                        status_validate: array_bills[count_bill].status_validate,
                    }

                    count_bill ++;
                    array_finish_bill.push(bill_month);
                }
            }
        });

    }else{
        array_bills.map(function(bill_obj, key) {
            amount = array_bills[count_bill].amount;
            date_aux = array_bills[count_bill].date;
            
            total_bill += Number(amount);
            var bill_month = {
                id: array_bills[count_bill].id,
                //date: date_aux,
                date: array_bills[count_bill].date,
                amount: amount,
                article: '',//article_obj,
                select_way_to_pay: array_bills[count_bill].way_to_pay,
                select_expiration: array_bills[count_bill].expiration,
                observations: array_bills[count_bill].observations,
                order_number: array_bills[count_bill].num_order,
                internal_observations: array_bills[count_bill].internal_observations,
                will_update: array_bills[count_bill].will_update,
                status_validate: array_bills[count_bill].status_validate,
            }

            count_bill ++;
            array_finish_bill.push(bill_month);
        });
    }

    custom_state.bill_obj.array_bills = array_finish_bill;
    custom_state.bill_obj.total_bill = total_bill;

    //Nombre completo del consultor
    custom_state.user_obj = response.data.consultant;

    //Nombre de la empresa
    custom_state.company_aux = response.data.company_aux;

    //Guardamos datos
    custom_state.proposal_bd_obj = proposal_submission_settings;
    custom_state.proposal_obj.array_dates = array_dates_prices;

    custom_state.status_view = 2;
    if(type == 2){
        custom_state.status_view = 3;
    }
    state.errors.type_error = 'get_info_proposal';
    state.errors.code = response.data.code;
    custom_state.is_change_get_info = 1;
    custom_state.id_company = response.data.proposal.id_company;
    if(type == 2){
        custom_state.proposal_obj.id_order = response.data.proposal.id
    }
}

//Rellenar objetos para el store y mostrar la información de las ordenes de información
function createObjectsStoreInfo({ state }, response, type){
    var custom_state = state.invoice_validations;

    if(response.data.proposal.is_custom){
        custom_state.num_custom_invoices = Number(response.data.proposal_bills.length);
    }

    var array_services = response.data.array_services;
    custom_state.proposal_obj.chapters.articles = [];
    custom_state.proposal_obj.chapters.dates_prices_aux = [];
    custom_state.bill_obj.array_bills = [];
    var array_dates_aux = [];
    var array_chapters = [];
    var proposal = response.data.proposal;

    if(array_services != undefined){
        array_services.forEach(function callback(service, index, array) {
            array_dates_aux.push(service.date);

            if(array_chapters.length == 0){
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
                    chapter_obj: service.chapter,
                    department_obj: proposal.department_obj
                }
                array_chapters.push({
                    id_chapter: service.chapter.id,
                    articles: [article],
                    articles_aux: [article],
                    chapter_obj: service.chapter
                });
    
            }else{
                var exist_1 = false;
                array_chapters.forEach(function callback(chapter, index, array) {
                    if(chapter.id_chapter == service.chapter.id){
                        exist_1 = true;
                        var exist_2 = false;
                        chapter.articles.forEach(function callback(article, index, array) {
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
                                chapter_obj: service.chapter,
                                department_obj: proposal.department_obj
                            }
                            chapter.articles.push(article);
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
                        chapter_obj: service.chapter,
                        department_obj: proposal.department_obj
                    }
                    array_chapters.push({
                        id_chapter: service.chapter.id,
                        articles: [article],
                        articles_aux: [article],
                        chapter_obj: service.chapter
                    });
                }
            }
            
        });
        custom_state.proposal_obj.chapters = array_chapters;
    }

    //Consultamos los totales
    var total_amount_global = 0;
    var total_individual_pvp = 0;
    var total_global = 0;
    var total_global_default = 0;
    custom_state.proposal_obj.chapters.map(function(chapter, key) {
        chapter.articles.map(function(article, key) {
            total_individual_pvp += article.article_obj.pvp;
            article.dates_prices.map(function(date_price, key) {
                date_price.arr_pvp_date.map(function(pvp_date, key) {
                    total_amount_global += pvp_date.arr_pvp.length;
                    pvp_date.arr_pvp.map(function(pvp, key) {
                        total_global += Number(pvp);
                        total_global_default += Number(article.article_obj.pvp);
                    });
                });
            });
        });
    });
    custom_state.proposal_obj.total_individual_pvp = total_individual_pvp;
    custom_state.proposal_obj.total_amount_global = total_amount_global;
    custom_state.proposal_obj.total_global_normal = total_global_default;
    custom_state.proposal_obj.total_global = total_global;
    
    custom_state.proposal_obj.array_consultants = response.data.array_consultants;

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
        custom_state.proposal_obj.chapters.map(function(articles_obj, key) {
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
        id_proposal_custom_aux: proposal.id_proposal_custom_aux,
        discount: proposal.discount,
        status: proposal.status,
        advertiser: proposal.advertiser,
        type_proposal: proposal.type_proposal
    }
    
    //Guardamos con un nuevo formato para las facturas los articulos
    var array_articles = [];
    custom_state.proposal_obj.chapters.map(function(chapters, key) {
        chapters.articles.map(function(article_obj, key) {
            article_obj.dates_prices.map(function(dates_prices_obj, key) {
                dates_prices_obj.arr_pvp_date.map(function(arr_pvp_date_obj, key) {
                    arr_pvp_date_obj.arr_pvp.map(function(arr_pvp_obj, key) {
                        var article_obj_aux = {
                            date: arr_pvp_date_obj.date,
                            article: article_obj,
                            id_chapter: chapters.chapter_obj.id,
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

    var date_aux = '';
    if(!response.data.proposal.is_custom){
        if(array_articles.length > 0){
            date_aux = array_articles[0].date;
        }
    }
    var amount = 0;
    var array_finish_bill = [];
    var last_key = 0;
    var total_bill = 0;
    custom_state.bill_obj.articles = array_articles;

    //Creamos el objeto factura
    var array_bills = response.data.proposal_bills;

    var count_bill = 0;
    if(!response.data.proposal.is_custom){
        array_articles.map(function(article_obj, key) {
            if(key == 0){
                if(response.data.proposal.is_custom){
                    amount = array_bills[count_bill].amount;
                    date_aux = array_bills[count_bill].date;
                }else{
                    amount = Number(array_bills[count_bill].amount);
                }
                
                total_bill += Number(array_bills[count_bill].amount);
                var bill_month = {
                    id: array_bills[count_bill].id,
                    date: date_aux,
                    amount: amount,
                    article: article_obj,
                    select_way_to_pay: array_bills[count_bill].way_to_pay,
                    select_expiration: array_bills[count_bill].expiration,
                    observations: array_bills[count_bill].observations,
                    order_number: array_bills[count_bill].num_order,
                    internal_observations: array_bills[count_bill].internal_observations,
                    will_update: array_bills[count_bill].will_update,
                    status_validate: array_bills[count_bill].status_validate,
                }

                count_bill ++;
                array_finish_bill.push(bill_month);

            }else{
                if(!response.data.proposal.is_custom){
                    if(date_aux == article_obj.date){
                        var is_break = false;
                        array_finish_bill.map(function(bill_obj, key) {
                            if(!is_break){
                                if(bill_obj.date == article_obj.date){
                                    if(bill_obj.article.id_chapter == article_obj.id_chapter){
                                        amount += Number(array_bills[count_bill].amount);
                                        total_bill += Number(array_bills[count_bill].amount);
                                        array_finish_bill[last_key].amount = amount;
                                        is_break = true;
                                    }
                                }
                            }
                        });

                        if(!is_break){
                            amount = 0;
                            date_aux = article_obj.date;
                            amount += Number(array_bills[count_bill].amount);
                            total_bill += Number(array_bills[count_bill].amount);
                            var bill_month = {
                                id: array_bills[count_bill].id,
                                date: date_aux,
                                amount: amount,
                                article: article_obj,
                                select_way_to_pay: array_bills[count_bill].way_to_pay,
                                select_expiration: array_bills[count_bill].expiration,
                                observations: array_bills[count_bill].observations,
                                order_number: array_bills[count_bill].num_order,
                                internal_observations: array_bills[count_bill].internal_observations,
                                will_update: array_bills[count_bill].will_update,
                                status_validate: array_bills[count_bill].status_validate,
                            }
                            array_finish_bill.push(bill_month);
                            count_bill++;
                            last_key = (array_finish_bill.length - 1);
                        }

                    }else{
                        amount = 0;
                        date_aux =  article_obj.date;
                        amount += Number(array_bills[count_bill].amount);
                        total_bill += Number(array_bills[count_bill].amount);
                        var bill_month = {
                            id: array_bills[count_bill].id,
                            date: date_aux,
                            amount: amount,
                            article: article_obj,
                            select_way_to_pay: array_bills[count_bill].way_to_pay,
                            select_expiration: array_bills[count_bill].expiration,
                            observations: array_bills[count_bill].observations,
                            order_number: array_bills[count_bill].num_order,
                            internal_observations: array_bills[count_bill].internal_observations,
                            will_update: array_bills[count_bill].will_update,
                            status_validate: array_bills[count_bill].status_validate,
                        }
                        count_bill++;
                        array_finish_bill.push(bill_month);
                        last_key = (array_finish_bill.length - 1);
                    }

                }else{
                    amount = array_bills[count_bill].amount;
                    total_bill += Number(array_bills[count_bill].amount);
                    var bill_month = {
                        id: array_bills[count_bill].id,
                        date: array_bills[count_bill].date,
                        amount: amount,
                        article: article_obj,
                        select_way_to_pay: array_bills[count_bill].way_to_pay,
                        select_expiration: array_bills[count_bill].expiration,
                        observations: array_bills[count_bill].observations,
                        order_number: array_bills[count_bill].num_order,
                        internal_observations: array_bills[count_bill].internal_observations,
                        will_update: array_bills[count_bill].will_update,
                        status_validate: array_bills[count_bill].status_validate,
                    }

                    count_bill ++;
                    array_finish_bill.push(bill_month);
                }
            }
        });

    }else{
        array_bills.map(function(bill_obj, key) {
            amount = array_bills[count_bill].amount;
            date_aux = array_bills[count_bill].date;
            
            total_bill += Number(amount);
            var bill_month = {
                id: array_bills[count_bill].id,
                date: date_aux,
                amount: amount,
                article: '',//article_obj,
                select_way_to_pay: array_bills[count_bill].way_to_pay,
                select_expiration: array_bills[count_bill].expiration,
                observations: array_bills[count_bill].observations,
                order_number: array_bills[count_bill].num_order,
                internal_observations: array_bills[count_bill].internal_observations,
                will_update: array_bills[count_bill].will_update,
                status_validate: array_bills[count_bill].status_validate,
            }

            count_bill ++;
            array_finish_bill.push(bill_month);
        });
    }

    custom_state.bill_obj.array_bills = array_finish_bill;
    custom_state.bill_obj.total_bill = total_bill;

    //Nombre completo del consultor
    custom_state.user_obj = response.data.consultant;

    //Nombre de la empresa
    custom_state.company_aux = response.data.company_aux;

    //Guardamos datos
    custom_state.proposal_bd_obj = proposal_submission_settings;
    custom_state.proposal_obj.array_dates = array_dates_prices;

    custom_state.status_view = 2;
    if(type == 2){
        custom_state.status_view = 3;
    }
    state.errors.type_error = 'get_info_proposal';
    state.errors.code = response.data.code;
    custom_state.is_change_get_info = 1;
    custom_state.id_company = response.data.proposal.id_company;
    if(type == 2){
        custom_state.proposal_obj.id_order = response.data.proposal.id
    }
}

//Rellenar objetos para el store y mostrar la información de las ordenes
function createObjectsStoreOrders({ state }, response, type){
    var custom_state = state.orders;

    if(response.data.proposal.is_custom){
        custom_state.num_custom_invoices = Number(response.data.proposal_bills.length);
    }

    var array_services = response.data.array_services;
    custom_state.proposal_obj.chapters.articles = [];
    custom_state.proposal_obj.chapters.dates_prices_aux = [];
    custom_state.bill_obj.array_bills = [];
    var array_dates_aux = [];
    var array_chapters = [];
    var proposal = response.data.proposal;

    if(array_services != undefined){
        array_services.forEach(function callback(service, index, array) {
            array_dates_aux.push(service.date);

            if(array_chapters.length == 0){
                var article = {
                    amount: 1,
                    article_obj: service.article,
                    dates: [service.date],
                    dates_prices: [{
                        arr_pvp_date: [{
                            date: service.date,
                            arr_pvp: [service.pvp],
                            arr_status_validate: [service.status_validate]
                        }],
                        date: changeFormatDate(service.date)
                    }],
                    total: service.pvp,
                    chapter_obj: service.chapter,
                    department_obj: proposal.department_obj
                }
                array_chapters.push({
                    id_chapter: service.chapter.id,
                    articles: [article],
                    articles_aux: [article],
                    chapter_obj: service.chapter
                });
    
            }else{
                var exist_1 = false;
                array_chapters.forEach(function callback(chapter, index, array) {
                    if(chapter.id_chapter == service.chapter.id){
                        exist_1 = true;
                        var exist_2 = false;
                        chapter.articles.forEach(function callback(article, index, array) {
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
                                                pvp_date.arr_status_validate.push(service.status_validate);
                                                exist_4 = true;
                                            }
                                        });
                                        if(!exist_4){
                                            var pvp_date = {
                                                date: service.date,
                                                arr_pvp: [service.pvp],
                                                arr_status_validate: [service.status_validate]
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
                                            arr_pvp: [service.pvp],
                                            arr_status_validate: [service.status_validate]
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
                                        arr_pvp: [service.pvp],
                                        arr_status_validate: [service.status_validate]
                                    }],
                                    date: changeFormatDate(service.date)
                                }],
                                total: service.pvp,
                                chapter_obj: service.chapter,
                                department_obj: proposal.department_obj
                            }
                            chapter.articles.push(article);
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
                                arr_pvp: [service.pvp],
                                arr_status_validate: [service.status_validate]
                            }],
                            date: changeFormatDate(service.date)
                        }],
                        total: service.pvp,
                        chapter_obj: service.chapter,
                        department_obj: proposal.department_obj
                    }
                    array_chapters.push({
                        id_chapter: service.chapter.id,
                        articles: [article],
                        articles_aux: [article],
                        chapter_obj: service.chapter
                    });
                }
            }
            
        });
        custom_state.proposal_obj.chapters = array_chapters;
    }

    //Consultamos los totales
    var total_amount_global = 0;
    var total_individual_pvp = 0;
    var total_global = 0;
    var total_global_default = 0;
    custom_state.proposal_obj.chapters.map(function(chapter, key) {
        chapter.articles.map(function(article, key) {
            total_individual_pvp += article.article_obj.pvp;
            article.dates_prices.map(function(date_price, key) {
                date_price.arr_pvp_date.map(function(pvp_date, key) {
                    total_amount_global += pvp_date.arr_pvp.length;
                    pvp_date.arr_pvp.map(function(pvp, key) {
                        total_global += Number(pvp);
                        total_global_default += Number(article.article_obj.pvp);
                    });
                });
            });
        });
    });
    custom_state.proposal_obj.total_individual_pvp = total_individual_pvp;
    custom_state.proposal_obj.total_amount_global = total_amount_global;
    custom_state.proposal_obj.total_global_normal = total_global_default;
    custom_state.proposal_obj.total_global = total_global;
    
    custom_state.proposal_obj.array_consultants = response.data.array_consultants;

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
        custom_state.proposal_obj.chapters.map(function(articles_obj, key) {
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
        id_proposal_custom_aux: proposal.id_proposal_custom_aux,
        discount: proposal.discount,
        status: proposal.status,
        advertiser: proposal.advertiser,
        type_proposal: proposal.type_proposal
    }
    
    //Guardamos con un nuevo formato para las facturas los articulos
    var array_articles = [];
    custom_state.proposal_obj.chapters.map(function(chapters, key) {
        chapters.articles.map(function(article_obj, key) {
            article_obj.dates_prices.map(function(dates_prices_obj, key) {
                dates_prices_obj.arr_pvp_date.map(function(arr_pvp_date_obj, key) {
                    arr_pvp_date_obj.arr_pvp.map(function(arr_pvp_obj, key) {
                        var article_obj_aux = {
                            date: arr_pvp_date_obj.date,
                            article: article_obj,
                            id_chapter: chapters.chapter_obj.id,
                            amount: arr_pvp_obj,
                            status_validate: arr_pvp_date_obj.arr_status_validate[key]
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

    var date_aux = '';
    if(!response.data.proposal.is_custom){
        if(array_articles.length > 0){
            date_aux = array_articles[0].date;
        }
    }
    var amount = 0;
    var array_finish_bill = [];
    var last_key = 0;
    var total_bill = 0;
    custom_state.bill_obj.articles = array_articles;

    //Creamos el objeto factura
    var array_bills = response.data.proposal_bills;

    var count_bill = 0;
    if(!response.data.proposal.is_custom){
        array_articles.map(function(article_obj, key) {
            if(key == 0){
                if(response.data.proposal.is_custom){
                    amount = array_bills[count_bill].amount;
                    date_aux = array_bills[count_bill].date;
                }else{
                    amount = Number(array_bills[count_bill].amount);
                }
                
                total_bill += Number(array_bills[count_bill].amount);
                var bill_month = {
                    id: array_bills[count_bill].id,
                    date: date_aux,
                    amount: amount,
                    article: article_obj,
                    select_way_to_pay: array_bills[count_bill].way_to_pay,
                    select_expiration: array_bills[count_bill].expiration,
                    observations: array_bills[count_bill].observations,
                    order_number: array_bills[count_bill].num_order,
                    internal_observations: array_bills[count_bill].internal_observations,
                    will_update: array_bills[count_bill].will_update,
                    status_validate: array_bills[count_bill].status_validate,
                }

                count_bill ++;
                array_finish_bill.push(bill_month);

            }else{
                if(!response.data.proposal.is_custom){
                    if(date_aux == article_obj.date){
                        var is_break = false;
                        array_finish_bill.map(function(bill_obj, key) {
                            if(!is_break){
                                if(bill_obj.date == article_obj.date){
                                    if(bill_obj.article.id_chapter == article_obj.id_chapter){
                                        amount += Number(array_bills[count_bill].amount);
                                        total_bill += Number(array_bills[count_bill].amount);
                                        array_finish_bill[last_key].amount = amount;
                                        is_break = true;
                                    }
                                }
                            }
                        });

                        if(!is_break){
                            amount = 0;
                            date_aux = article_obj.date;
                            amount += Number(array_bills[count_bill].amount);
                            total_bill += Number(array_bills[count_bill].amount);
                            var bill_month = {
                                id: array_bills[count_bill].id,
                                date: date_aux,
                                amount: amount,
                                article: article_obj,
                                select_way_to_pay: array_bills[count_bill].way_to_pay,
                                select_expiration: array_bills[count_bill].expiration,
                                observations: array_bills[count_bill].observations,
                                order_number: array_bills[count_bill].num_order,
                                internal_observations: array_bills[count_bill].internal_observations,
                                will_update: array_bills[count_bill].will_update,
                                status_validate: array_bills[count_bill].status_validate,
                            }
                            array_finish_bill.push(bill_month);
                            count_bill++;
                            last_key = (array_finish_bill.length - 1);
                        }

                    }else{
                        amount = 0;
                        date_aux =  article_obj.date;
                        amount += Number(array_bills[count_bill].amount);
                        total_bill += Number(array_bills[count_bill].amount);
                        var bill_month = {
                            id: array_bills[count_bill].id,
                            date: date_aux,
                            amount: amount,
                            article: article_obj,
                            select_way_to_pay: array_bills[count_bill].way_to_pay,
                            select_expiration: array_bills[count_bill].expiration,
                            observations: array_bills[count_bill].observations,
                            order_number: array_bills[count_bill].num_order,
                            internal_observations: array_bills[count_bill].internal_observations,
                            will_update: array_bills[count_bill].will_update,
                            status_validate: array_bills[count_bill].status_validate,
                        }
                        count_bill++;
                        array_finish_bill.push(bill_month);
                        last_key = (array_finish_bill.length - 1);
                    }

                }else{
                    amount = array_bills[count_bill].amount;
                    total_bill += Number(array_bills[count_bill].amount);
                    var bill_month = {
                        id: array_bills[count_bill].id,
                        date: array_bills[count_bill].date,
                        amount: amount,
                        article: article_obj,
                        select_way_to_pay: array_bills[count_bill].way_to_pay,
                        select_expiration: array_bills[count_bill].expiration,
                        observations: array_bills[count_bill].observations,
                        order_number: array_bills[count_bill].num_order,
                        internal_observations: array_bills[count_bill].internal_observations,
                        will_update: array_bills[count_bill].will_update,
                        status_validate: array_bills[count_bill].status_validate,
                    }

                    count_bill ++;
                    array_finish_bill.push(bill_month);
                }
            }
        });

    }else{
        array_bills.map(function(bill_obj, key) {
            amount = array_bills[count_bill].amount;
            date_aux = array_bills[count_bill].date;
            
            total_bill += Number(amount);
            var bill_month = {
                id: array_bills[count_bill].id,
                date: date_aux,
                amount: amount,
                article: '',//article_obj,
                select_way_to_pay: array_bills[count_bill].way_to_pay,
                select_expiration: array_bills[count_bill].expiration,
                observations: array_bills[count_bill].observations,
                order_number: array_bills[count_bill].num_order,
                internal_observations: array_bills[count_bill].internal_observations,
                will_update: array_bills[count_bill].will_update,
                status_validate: array_bills[count_bill].status_validate,
            }

            count_bill ++;
            array_finish_bill.push(bill_month);
        });
    }

    custom_state.bill_obj.array_bills = array_finish_bill;
    custom_state.bill_obj.total_bill = total_bill;


    var array_bills_custom = [];
    var total_bill = 0;
    if(!response.data.proposal.is_custom){
        //array_articles.map(function(article_obj, article_obj_key) {
            array_bills.map(function(bill_obj, bill_obj_key) {
                var bill_month = {
                    id: bill_obj.id,
                    date: bill_obj.date,
                    amount: bill_obj.amount,
                    select_way_to_pay: bill_obj.way_to_pay,
                    select_expiration: bill_obj.expiration,
                    observations: bill_obj.observations,
                    order_number: bill_obj.num_order,
                    internal_observations: bill_obj.internal_observations,
                    will_update: bill_obj.will_update,
                    status_validate: bill_obj.status_validate,
                }
                total_bill += bill_obj.amount;
                array_bills_custom.push(bill_month);

            });
        //});

    }else{

    }
    custom_state.bill_obj.array_bills = array_bills_custom;
    custom_state.bill_obj.total_bill = total_bill;

    //Nombre completo del consultor
    custom_state.user_obj = response.data.consultant;

    //Nombre de la empresa
    custom_state.company_aux = response.data.company_aux;

    //Guardamos datos
    custom_state.proposal_bd_obj = proposal_submission_settings;
    custom_state.proposal_obj.array_dates = array_dates_prices;

    custom_state.status_view = 2;
    if(type == 2){
        custom_state.status_view = 3;
    }
    state.errors.type_error = 'get_info_proposal';
    state.errors.code = response.data.code;
    custom_state.is_change_get_info = 1;
    custom_state.id_company = response.data.proposal.id_company;
    if(type == 2){
        custom_state.proposal_obj.id_order = response.data.proposal.id
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

export default actions;
