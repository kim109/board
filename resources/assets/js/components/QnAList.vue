<template>
  <div class="content-box">
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a href="#" class="nav-link" :class="{active : category == 0}" @click.prevent="category= 0">전체보기</a>
      </li>
      <li v-for="c in categories" :key="c.id" class="nav-item">
        <a href="#" class="nav-link" :class="{active : category == c.id}" @click.prevent="category= c.id">{{ c.name }}</a>
      </li>
    </ul>
    <table class="table">
      <colgroup>
        <col />
        <col style="width:6em" />
        <col style="width:6em" />
        <col style="width:6em" />
      </colgroup>
      <thead>
        <tr class="text-center">
          <th>제목</th>
          <th>조회수</th>
          <th>작성자</th>
          <th>날짜</th>
        </tr>
      </thead>
      <tbody>
        <template v-if="lists.length">
          <tr v-for="(row) in lists" :key="row.id">
            <td class="text-truncate">
              <a :href="base+'/'+row.id">
                <span class="text-primary">[{{ row.category.name }}]</span>
                {{ row.subject }} ({{ row.comments_count.toLocaleString() }})
              </a>
            </td>
            <td class="text-center">{{ row.hits.toLocaleString() }}</td>
            <td class="text-center">{{ maskName(row.user.name) }}</td>
            <td class="text-center" :title="row.created_at">{{ dateAt(row.created_at) }}</td>
          </tr>
        </template>
        <tr v-else>
          <td colspan="4" class="text-center">등록된 글이 없습니다.</td>
        </tr>
      </tbody>
    </table>
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
    </div>
  </div>
</template>

<script>
import moment from 'moment'
moment.locale('ko')

export default {
  props: {
    categories: Array,
    keyword: String
  },
  data: function () {
    return {
      base: window.location.pathname,
      category: 0,
      rows: [],
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
    this.$http.get(this.base+'/list')
    .then((response) => {
      this.rows = response.data
    })
  },
  methods: {
    dateAt: function(date) {
      return moment(date).format('YY.MM.DD')
    },
    maskName: function(name) {
      let masking = name.charAt(0)

      if (name.length == 2) {
        masking = masking+'*'
      } else if (name.length > 2) {
        masking = masking + '*'.repeat(name.length-2) + name.substr(-1)
      }

      return masking
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
  .content-box {
    .nav {
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

    table {
      table-layout: fixed;

      td {
        border-top: none;
        padding: 0.5rem;
      }
    }
  }
</style>