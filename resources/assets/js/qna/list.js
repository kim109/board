require('../bootstrap')

const app = new Vue({
  el: '#content',
  components: {
    'summary-articles': require('../components/Summary.vue'),
    'articles-list': require('../components/QnAList.vue')
  },
  data: function () {
    return {
      categories: [],
      keyword: null
    }
  },
  beforeMount: function() {
    this.$http.get(window.location.pathname+'/categories')
    .then((response) => {
      this.categories = response.data
    })
  },
  methods: {
    search: function() {
      this.keyword = document.getElementById('keyword').value
    }
  }
})
