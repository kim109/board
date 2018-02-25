require('../bootstrap')

const app = new Vue({
  el: '#content',
  components: {
    'summary-articles': require('../components/Summary.vue'),
    'media-list': require('../components/MediaList.vue')
  }
})
