<template>
  <div class="summary-card clearfix">
    <div class="float-right"><img src="/images/summary-right.jpg" width="348" height="310"></div>
    <div class="pt-4 px-4 articles-list">
      <div class="media" v-if="thumbnail_data">
        <a :href="'/'+thumbnail_data.board+'/'+thumbnail_data.id">
          <img class="mr-3" :src="'/thumbnail/'+thumbnail_data.thumbnail_id+'?w=120&h=120'" :alt="thumbnail_data.subject">
        </a>
        <div class="media-body">
          <h5 class="mt-0">
            <a :href="'/'+thumbnail_data.board+'/'+thumbnail_data.id">
              <span class="text-primary">[{{ thumbnail_data.category.name }}]</span>
              {{ thumbnail_data.subject }} ({{ thumbnail_data.comments_count.toLocaleString() }})
            </a>
          </h5>
          <div class="row">
            <div class="col">
                <label class="text-primary">조회수</label>
                {{ thumbnail_data.hits.toLocaleString() }}
              </div>
              <div class="col text-right mr-3">
                <label class="text-primary">작성자</label>
                {{ thumbnail_data.user.name }}
              </div>
          </div>
        </div>
      </div>
      <hr>
      <ul class="list-unstyled">
        <li v-for="article in list_data" :key="'s-'+article.id" class="mb-2">
          <a :href="'/'+article.board+'/'+article.id">
            <span class="text-primary">[{{ article.category.name }}]</span>
            {{ article.subject }} ({{ article.comments_count.toLocaleString() }})
          </a>
        </li>
      </ul>
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
  computed: {
    thumbnail_data: function () {
      return _.find(this.articles, (article) => { return article.thumbnail_id })
    },
    list_data: function () {
      let data
      data = _.filter(this.articles, (article) => {
        return article != this.thumbnail_data
      })

      return _.orderBy(data, ['created_at'], ['desc'])
    }
  },
  beforeMount: function() {
    this.$http.get('/home/summary-articles')
    .then((response) => {
      this.articles = response.data
    })
  }
}
</script>

<style scoped>
  .summary-card {
    border: 5px solid #148fed;
    background: white;
  }
  .articles-list {
    margin-right: 348px;
  }

  a {
    text-decoration: none;
    color: black;
  }
  a:hover { font-weight: bold; }
</style>
