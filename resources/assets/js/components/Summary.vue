<template>
  <div class="summary-card">
    <div class="row no-gutters">
      <div class="col pt-4 px-4">
        <ul class="list-unstyled">
          <li v-for="article in articles" :key="'s-'+article.id" class="mb-2">
            <a :href="'/'+article.board+'/'+article.id">
              <span class="text-primary">[{{ article.category.name }}]</span>
              {{ article.subject }} ({{ article.comments_count.toLocaleString() }})
            </a>
          </li>
        </ul>
      </div>
      <div class="d-none d-lg-block">
        <img src="/images/summary-right.jpg" width="348" height="310">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      articles: null
    }
  },
  beforeMount: function() {
    this.$http.get('/home/summary-articles')
    .then((response) => {
      this.articles = _.orderBy(response.data, ['created_at'], ['desc'])
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
