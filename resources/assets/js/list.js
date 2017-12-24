require('./bootstrap')

const app = new Vue({
  el: '#content',
  components: {
    'summary-articles': require('./components/Summary.vue'),
    'articles-list': require('./components/List.vue')
  }
})
