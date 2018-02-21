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
      <table class="table">
        <colgroup>
          <col />
          <col style="width:6em" />
          <col style="width:6em" />
          <col style="width:7em" />
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
              <td class="text-center">{{ row.user.name }}</td>
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
        <div class="col-2 text-right" v-if="writable == 1">
          <a :href="base+'/create'" class="btn btn-sm btn-primary">
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
  beforeMount: function() {
    this.$http.get(this.base+'/list')
    .then((response) => {
      this.rows = response.data
    })
  },
  methods: {
    dateAt: function(date) {
        let now = moment();
        let d = moment(date);

        return d.format('YY. M. D');
    },
    search: function() {
      let keyword = document.getElementById('keyword').value
      this.$http.get(this.base+'/list', {
        params: { keyword: keyword }
      })
      .then((response) => {
        this.rows = response.data
      })
    }
  }
}
</script>

<style scoped>
  table {
    table-layout: fixed;
  }
  table td {
    border-top: none;
    padding: 0.5rem;
  }
</style>