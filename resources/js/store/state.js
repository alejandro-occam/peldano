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
            show_view_state: 1,
            filter: {
                array_sectors: null,
                array_brands: null,
                array_products: null,
            },
            form: {
                array_areas: null,
                array_sectors: null,
                array_brands: null,
                array_products: null,
                array_articles: null
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
            products:[
                {
                    product_obj: null,
                    articles: [],
                    articles_aux: [],
                    total_global: 0,
                    total_amount_global: 0,
                    total_individual_pvp: 0,
                }
            ],
            is_change: false,
            array_dates: []
        },
        bill_obj: {
            articles: [],
            array_bills: []
        },
        user_obj: {
            name: null,
            surname: null
        }
    },
    errors: {
        code: 0,
        type_error: ''
    }
};

export default state;