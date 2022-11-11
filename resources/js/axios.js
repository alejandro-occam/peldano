import axios from "axios";
import Consts from "./consts";

const http = axios.create({
    baseURL: Consts.defaultApiRoute,
});

export default http;
