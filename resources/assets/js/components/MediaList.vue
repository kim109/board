<template>
  <div>
    <div class="row">
      <div class="col-12 col-md-6 offset-md-6">
        <div class="input-group">
          <input type="search" id="keyword" class="form-control" placeholder="검색" aria-label="Search for..." @keyup.enter="search">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" @click="search">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="content-box mt-3">
      <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
          <a href="#" class="nav-link" :class="{active : category == 0}" @click.prevent="category= 0">전체보기</a>
        </li>
        <li v-for="c in categories" :key="c.id" class="nav-item">
          <a href="#" class="nav-link" :class="{active : category == c.id}" @click.prevent="category= c.id">{{ c.name }}</a>
        </li>
      </ul>

      <div class="row p-3">
        <div class="media col-md-6 mb-3" v-for="(row) in lists" :key="row.id">
          <a :href="base+'/'+row.id"><img class="mr-3" :src="'/thumbnail/'+row.thumbnail_id+'?w=120&h=120'" :alt="row.subject"></a>
          <div class="media-body">
            <div class="my-3 font-weight-bold">
              <a :href="base+'/'+row.id">{{ row.subject }} ({{ row.comments_count.toLocaleString() }})</a>
            </div>
            <div class="row small">
              <div class="col-12">
                <label class="text-primary">작성일</label>
                {{ row.created_at }}
              </div>
              <div class="col">
                <label class="text-primary">조회수</label>
                {{ row.hits.toLocaleString() }}
              </div>
              <div class="col text-right mr-3">
                <label class="text-primary">작성자</label>
                {{ row.user.name }}
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="p-3 row">
        <div class="offset-md-2 col-md-8 text-center">
          <nav v-if="this.totalpage > 1">
            <ul class="pagination pagination-sm justify-content-center">
              <li class="page-item" :class="[page == 0 ? 'disabled' : '']">
                <a class="page-link" href="#" aria-label="Previous" @click.prevent="page--">
                  <i class="fas fa-angle-left"></i>
                  <span class="sr-only">Previous</span>
                </a>
              </li>
              <li class="page-item"  v-for="n in totalpage" :key="n" :class="[(n-1) == page ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="page = n-1">{{ n }}</a>
              </li>
              <li class="page-item" :class="[page+1 == totalpage ? 'disabled' : '']">
                <a class="page-link" href="#" aria-label="Next" @click.prevent="page++">
                  <i class="fas fa-angle-right"></i>
                  <span class="sr-only">Next</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="col-2 text-right" v-if="writable == 1">
          <a :href="base+'/create'" class="btn btn-primary">
            <i class="fas fa-pencil-alt"></i> 글쓰기
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
moment.locale('ko')

export default {
  props: {
    writable: {
      required: true
    },
  },
  data: function () {
    return {
      base: window.location.pathname,
      categories: [],
      rows: [],
      category: 0,
      keyword: null,
      perpage: 15,
      page: 0
    }
  },
  computed: {
    lists: function () {
      let start = this.page * this.perpage
      let end = start + this.perpage

      return this.rows.slice(start, end)
    },
    totalpage: function () {
      return Math.ceil(this.rows.length / this.perpage)
    }
  },
  watch: {
    category: function() {
      this.load()
    },
    keyword: function() {
      this.load()
    }
  },
  beforeMount: function() {
    this.$http.get(this.base+'/categories')
    .then((response) => {
      this.categories = response.data
    })

    this.load()
  },
  methods: {
    search: function() {
      this.keyword = document.getElementById('keyword').value
    },
    load: function() {
      this.$http.get(this.base+'/list', {
        params: { keyword: this.keyword, category: this.category }
      })
      .then((response) => {
        this.rows = response.data
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .nav {
    border-bottom: 1px solid #cdcdcd;

    .nav-item {
      border-right: 1px solid #cdcdcd;
      &:last-child {
        border-right: none;
      }

      .nav-link.active {
        color: white;
        background-color: #505767;
        border-radius: 0;
      }
    }
  }
</style>