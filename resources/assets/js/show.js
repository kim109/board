require('./bootstrap')
import moment from 'moment'

moment.locale('ko')
Vue.prototype.moment = moment

const app = new Vue({
  el: '#content',
  components: {
    'summary-articles': require('./components/Summary.vue'),
    'comments': require('./components/Comment.vue')
  },
  data: {
    user: null,
    comments: null
  },
  mounted: function() {
    this.loadComments()
  },
  methods: {
    loadComments: function() {
      let url = window.location.pathname+'/comments'
      this.$http.get(url).then((response) => {
        this.user = response.data.user; 
        this.comments = response.data.comments
      })
    },
    comment: function() {
        let url = window.location.pathname+'/comments'
        let content = document.getElementById('comment-content').value.trim()
        this.$http.post(url, { 'content': content })
            .then((response) => {
                document.getElementById('comment-content').value = ''
                this.loadComments()
            });
    },
    destory: function(event) {
      if (confirm('게시글을 삭제하시겠습니까?')) {
        this.$http.delete(event.currentTarget.getAttribute('href'))
        .then((response) => {
          location.href = response.data.list
        });
      }
    }
  }
})


// import Vue from 'vue'
// import axios from 'axios'
// import moment from 'moment'

// // CSRF 토큰 설정
// axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// let token = document.head.querySelector('meta[name="csrf-token"]');
// if (token) {
//     axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }
// Vue.prototype.$http = axios

// moment.locale('ko')
// Vue.prototype.moment = moment

// document.addEventListener("DOMContentLoaded", function(event) {
//     let comments = new Vue({
//         el: '#comments',
//         components: {
//             'comment': require('./components/Comment.vue')
//         },
//         data: {
//             user: null,
//             comments: null
//         },
//         created: function() {
//             this.loadComments();
//         },
//         methods: {
//             loadComments: function() {
//                 let url = window.location.pathname+'/comments';
//                 this.$http.get(url)
//                     .then((response) => {
//                         this.user = response.data.user;
//                         this.comments = response.data.comments;
//                     });
//             },
//             comment: function() {
//                 let url = window.location.pathname+'/comments';
//                 let content = document.getElementById('comment-content').value.trim();
//                 this.$http.post(url, { 'content': content })
//                     .then((response) => {
//                         document.getElementById('comment-content').value = '';
//                         this.loadComments();
//                     });
//             }
//         }
//     });

//     if (document.getElementById('delete')) {
//         document.getElementById('delete').onclick = function(e) {
//             e.preventDefault();

//             if (confirm('작성하신 글을 삭제하시겠습니까?')) {
//                 axios.delete(this.getAttribute('href'))
//                     .then((response) => {
//                          location.href = response.data.list;
//                     });
//             }
//         }
//     }
// });
