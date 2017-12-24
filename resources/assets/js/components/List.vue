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
    <div class="content-box mt-3">
      <table class="table">
        <colgroup>
          <col />
          <col style="width:6em" />
          <col style="width:6em" />
          <col style="width:5em" />
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
          <tr v-for="(row) in rows" :key="row.id">
            <td>
              <a :href="base+'/'+row.id">
                <span class="text-primary">[{{ row.category.name }}]</span>
                {{ row.subject }} ({{ row.comments_count.toLocaleString() }})
              </a>
            </td>
            <td class="text-center">{{ row.hits.toLocaleString() }}</td>
            <td class="text-center">{{ row.user.name }}</td>
            <td class="text-center" :title="row.created_at">{{ dateAt(row.created_at) }}</td>
          </tr>
        </tbody>
      </table>
      <div class="p-3 row">
        <div class="offset-md-2 col-md-8 text-center">
           페이징
        </div>
        <div class="col-2 text-right" v-if="writable == 1">
          <a :href="base+'/create'" class="btn btn-sm btn-primary">글쓰기</a>
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
      rows: null
    }
  },
  methods: {
    dateAt: function(date) {
        let now = moment();
        let d = moment(date);

        if (now.diff(d, 'days', true) < 1) {
            return d.format('HH:mm');
        } else {
            return d.format('MM/DD');
        }
    }
  },
  beforeMount: function() {
    this.$http.get(this.base+'/list')
    .then((response) => {
      this.rows = response.data
    })
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