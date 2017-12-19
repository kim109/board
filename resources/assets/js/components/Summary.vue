<template>
  <div class="summary-card clearfix">
    <div class="float-right"><img src="images/summary-right.jpg" width="348" height="310"></div>
    <div class="py-4 px-5 articles-list">
      <div class="media">
        <img class="mr-3" src="" alt="Generic placeholder image">
        <div class="media-body">
          <h5 class="mt-0">Media heading</h5>
          Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
        </div>
      </div>
      <hr>
      <ul class="list-unstyled">
        <li>aa</li>
        <li>bb</li>
        <li>cc</li>
        <li>dd</li>
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
  mounted: function() {
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
</style>
