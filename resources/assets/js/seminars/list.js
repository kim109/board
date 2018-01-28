require('../bootstrap')

const app = new Vue({
  el: '#content',
  components: {
    'media-list': require('../components/MediaList.vue')
  }
})
