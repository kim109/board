require('./bootstrap');
require('jquery-confirm');

import Vue from 'vue';

document.addEventListener("DOMContentLoaded", function(event) {
    let comments = new Vue({
        el: '#comments',
        components: {
            'comment': require('./components/Comment.vue')
        },
        data: {
            comments: null
        },
        created: function() {
            this.loadComments();
        },
        methods: {
            loadComments: function () {
                $.get(window.location.pathname+"/comments", (data) => {
                    this.comments = data;
                }, 'json');
            }
        }
    });
});
