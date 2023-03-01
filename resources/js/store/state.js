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
        /*articles: {
            show_view_state: 1,
            filter: {
                array_sectors: null,
                array_brands: null,
                array_products: null,
            },
            form: {
                array_departments: null,
                array_sections: null, 
                array_channels: null,
                array_projects: null,
                array_batchs: null
            },
            is_update: 0,
            article_obj: {
                name: null
            },
            search_articles: '',
        },*/
        articles: {
            show_view_state: 1,
            filter: {
                array_departments: null,
                array_sections: null, 
                array_channels: null,
                array_projects: null,
            },
            form: {
                array_departments: null,
                array_sections: null, 
                array_channels: null,
                array_projects: null,
                array_batchs: null
            },
            is_update: 0,
            article_obj: {
                name: null
            },
            search_articles: '',
        }
    },
    proposals: {
        array_users: null,
        array_companies: null,
        status_view: 1,
        proposal_obj: {
            chapters:[
                {
                    chapter_obj: null,
                    articles: [],
                    articles_aux: [],
                    total_global: 0,
                    total_amount_global: 0,
                    total_individual_pvp: 0,
                }
            ],
            is_change: false,
            array_dates: [],
            array_consultants: []
        },
        proposal_bd_obj: null,
        bill_obj: {
            articles: [],
            array_bills: []
        },
        user_obj: {
            name: null,
            surname: null
        },
        num_custom_invoices: 0,
        is_change_get_info: 0,
        id_company: 0,
        html_proposal_list: ''
    },
    orders: {
        array_users: null,
        array_companies: null,
        status_view: 1,
        proposal_obj: {
            chapters:[
                {
                    chapter_obj: null,
                    articles: [],
                    articles_aux: [],
                    total_global: 0,
                    total_amount_global: 0,
                    total_individual_pvp: 0,
                }
            ],
            is_change: false,
            array_dates: [],
            array_consultants: []
        },
        proposal_bd_obj: null,
        bill_obj: {
            articles: [],
            array_bills: []
        },
        user_obj: {
            name: null,
            surname: null
        },
        num_custom_invoices: 0,
        is_change_get_info: 0,
        id_company: 0,
        html_orders_list: ''
    },
    reports: {
        status_view: 1,
        array_dates: [],
        array_bills_orders: [],
        percent_new: [],
        percent_old: [],
        period_new: '',
        period_old: '',
        total_amount: 0,
    },
    invoice_validations: {
        array_bill_orders: [{
            array_articles: []
        }]
    },
    errors: {
        code: 0,
        type_error: ''
    }
};

export default state;