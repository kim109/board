<template>
  <div class="summary-card">
    <div class="row no-gutters">
      <div class="col p-4">
        <ul class="list-unstyled">
          <li v-for="article in articles" :key="article.id">
            <a :href="'/'+article.board+'/'+article.id">
              <span class="text-primary">[{{ article.category.name }}]</span>
              {{ article.subject }}
              <span v-if="type == 'home'">- {{ maskName(article.user.name) }}</span>
              <span v-else>({{ article.comments_count.toLocaleString() }})</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="d-none d-lg-block">
        <img src="/images/summary-right.jpg" height="225">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      required: true
    },
  },
  data: function () {
    return {
      articles: null
    }
  },
  beforeMount: function() {
    this.$http.get('/'+this.type+'/popularity')
    .then((response) => {
      this.articles = _.orderBy(response.data, ['hits'], ['desc'])
    })
  },
  methods: {
    maskName: function(name) {
      let masking = name.charAt(0)

      if (name.length == 2) {
        masking = masking+'*'
      } else if (name.length > 2) {
        masking = masking + '*'.repeat(name.length-2) + name.substr(-1)
      }

      return masking
    }
  }
}
</script>

<style lang="scss" scoped>
  .summary-card {
    border: 5px solid #148fed;
    background: white;

    ul {
      margin-bottom: 0;

      li {
        margin-bottom: 0.5rem;
        &:last-child {
          margin-bottom: 0;
        }

        a {
          text-decoration: none;
          color: black;
          &:hover {
            font-weight: bold;
          }
        }
      }
    }
  }
</style>
