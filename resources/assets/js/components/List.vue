<template>
  <div>
    <div class="row">
      <div class="col-12 col-md-6 offset-md-6">
        <div class="input-group">
          <input type="Search" class="form-control" placeholder="검색" aria-label="Search for...">
          <span class="input-group-btn">
            <button class="btn btn-secondary" type="button">Go!</button>
          </span>
        </div>
      </div>
    </div>
    <table>

    </table>
  </div>
</template>

<script>
export default {
  props: {
    url: {
      required: true
    },
    writable: {
      // type: 'Boolean',
      required: true
    },
  },
  data: function () {
    return {
      list: null
    }
  },
  beforeMount: function() {
    this.$http.get(this.url)
    .then((response) => {
      this.list = response.data
    })
  }
}
</script>

<style>

</style>
