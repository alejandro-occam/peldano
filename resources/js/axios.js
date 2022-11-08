import axios from "axios";

const http = axios.create({
    //baseURL: "http://seahold_front.test",
    //baseURL: "http://seahold.io/",
    baseURL: 'https://peldano.occamagenciadigital.com/',
});

export default http;
