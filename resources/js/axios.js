import axios from "axios";

const http = axios.create({
    //baseURL: "http://seahold_front.test",
    //baseURL: "http://seahold.io/",
    baseURL: 'http://127.0.0.1:8000/',
});

export default http;
