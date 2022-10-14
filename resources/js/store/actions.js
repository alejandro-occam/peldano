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

    //Listar calendarios para aexportar 
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

    //Consultar sectores
    async getSectors({ state }){
        try {
            const response = await http({
                url: "/admin/get_sectors",
                method: 'get'
            });

            state.config.articles.array_sectors = response.data.array_sectors;

        } catch (error) {
            console.error(error);

            return error;
        }
    },
    
    //Consultar marcsas
    async getBrands({ state }, params){
        try {
            const response = await http({
                url: "/admin/get_brands/" + params,
                method: 'get'
            });

            state.config.articles.array_brands = response.data.array_brands;

        } catch (error) {
            console.error(error);

            return error;
        }
    },
}

export default actions;
