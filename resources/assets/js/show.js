// require('./bootstrap');

import Vue from 'vue';
import axios from 'axios';

// CSRF 토큰 설정
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
Vue.prototype.$http = axios;

document.addEventListener("DOMContentLoaded", function(event) {
    let comments = new Vue({
        el: '#comments',
        components: {
            'comment': require('./components/Comment.vue')
        },
        data: {
            user: null,
            comments: null
        },
        created: function() {
            this.loadComments();
        },
        methods: {
            loadComments: function () {
                let url = window.location.pathname+'/comments';
                this.$http.get(url)
                    .then((response) => {
                        this.user = response.data.user;
                        this.comments = response.data.comments;
                    });
            }
        }
    });
});
