require('../bootstrap')
import moment from 'moment'

moment.locale('ko')
Vue.prototype.moment = moment

const app = new Vue({
  el: '#content',
  components: {
    'summary-articles': require('../components/Summary.vue'),
    'comments': require('../components/Comment.vue')
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
    },
    destoryAnswer: function(event) {
      if (confirm('답변을 삭제하시겠습니까?')) {
        this.$http.delete(event.currentTarget.getAttribute('href'))
        .then((response) => {
          location.href = response.data.list
        });
      }
    }
  }
})
