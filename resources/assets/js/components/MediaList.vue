<template>
  <div>
    <div class="row">
      <!-- 검색 Input -->
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
      <!-- 카테고리 Tab -->
      <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
          <a href="#" class="nav-link" :class="{active : category == 0}" @click.prevent="category= 0">전체보기</a>
        </li>
        <li v-for="c in categories" :key="c.id" class="nav-item">
          <a href="#" class="nav-link" :class="{active : category == c.id}" @click.prevent="category= c.id">{{ c.name }}</a>
        </li>
      </ul>

      <div class="row p-3">
        <!-- 글목록 -->
        <template v-if="lists.length">
          <div class="col-3 mb-3" v-for="(row) in lists" :key="row.id">
            <a :href="base+'/'+row.id">
              <div class="image">
                <img :src="'/thumbnail/'+row.thumbnail_id" :alt="row.subject" class="img img-responsive full-width" />
              </div>
              <div>
                <span class="text-primary">[{{ row.category.name }}]</span>
                {{ row.subject }} ({{ row.comments_count.toLocaleString() }})
              </div>
            </a>
          </div>
        </template>
        <div class="col text-center my-5" v-else>등록된 글이 없습니다.</div>
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
    perpage: {
      required: true
    }
  },
  data: function () {
    return {
      base: window.location.pathname,
      categories: [],
      rows: [],
      category: 0,
      keyword: null,
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

  .image {
    position:relative;
    overflow:hidden;
    padding-bottom:100%;

    img {
      position: absolute;
      max-width: 100%;
      max-height: 100%;
      top: 50%;
      left: 50%;
      transform: translateX(-50%) translateY(-50%);
    }
  }
</style>