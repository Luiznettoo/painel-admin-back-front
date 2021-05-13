import axios from 'axios';


window.$ = window.jQuery = require('jquery');
require('jquery-confirm');

window._ = require('lodash');

window.axios = axios;
window._axios = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/v1/',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
    }
});

let cookie = window.localStorage.getItem('authToken');
if (cookie) {
    _axios.defaults.headers.Authorization = `Basic ${cookie}`;
    window.token = cookie;
}
