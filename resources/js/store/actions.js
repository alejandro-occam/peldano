import http from "../axios";

const actions = {
    //Listado de pares por exchangeh
    async getListUsers({ state }, params){
        try {
            const response = await http({
                url: "/list_user",
            });


        } catch (error) {
            console.error(error);

            return error;
        }
    }
}

export default actions;
