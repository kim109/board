require('../bootstrap')

const app = new Vue({
  el: '#summary',
  components: {
    'media-list': require('../components/Messenger.vue'),
    'jisik-list': require('../components/MessengerInsu.vue')
  }
})
