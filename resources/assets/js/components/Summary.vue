<template>
  <div class="summary-card">
    <div class="row no-gutters">
      <div class="col pt-4 px-4">
        <ul class="list-unstyled">
          <li v-for="article in articles" :key="article.id" class="mb-2">
            <a :href="'/'+type+'/'+article.id">
              <span class="text-primary">[{{ article.category.name }}]</span>
              {{ article.subject }} ({{ article.comments_count.toLocaleString() }})
            </a>
          </li>
        </ul>
      </div>
      <div class="d-none d-lg-block">
        <img src="/images/summary-right.jpg" height="200">
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
  }
}
</script>

<style scoped>
  .summary-card {
    border: 5px solid #148fed;
    background: white;
  }
  a {
    text-decoration: none;
    color: black;
  }
  a:hover { font-weight: bold; }
</style>
