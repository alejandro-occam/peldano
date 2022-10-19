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
            article: {
                area: null,
                sector_obj: null,
                brand: null,
                product: null,
                article_obj: null,
                amount: null,
                dates: null,
            }
        },
        user_obj: {
            name: null,
            surname: null
        }
    },
    errors: {
        code: 0,
        type_error: ''
    },
    // Datatable defaults
    datatable_defaults: {
        no_results: "No se encontraron resultados",
        lazy: true,
        loading: false,
        paginator: true,
        rows: 5,
        rowsPerPageOptions: [5, 10, 20],
        rowHover: true,
        paginatorTemplate:
            "PrevPageLink PageLinks NextPageLink RowsPerPageDropdown",
        currentPageReportTemplate:
            "Mostrando del {first} al {last}, de un total de {totalRecords} registros",
        scrollable: true,
        scrollHeight: "600px",
        currentPage: 0,
        responsiveLayout: "scroll",
    },
};

export default state;