const state = {
    config: {
        users:{
            show_view_state: 1,
            array_positions: null,
            array_roles: null,
            user_obj: {
                name: '',
                surname: '',
                email: ''
            },
            is_update: 0
        },
        calendars:{
            show_view_state: 1,
            calendar_obj: null,
            is_update: 0,
            html_calendar: null
        },
        articles: {
            array_sectors: null,
            array_brands: null
        }
    },
    errors: {
        code: 0,
        type_error: ''
    }
};

export default state;