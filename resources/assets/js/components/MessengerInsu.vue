<template>
  <div>    
    <div class="content-box mt-3">
        <div>
                <ul>
        <template v-if="lists.length">
            <li v-for="(row) in lists" :key="row.id">              
                <a :href="base+'/'+row.id">
                  <span class="text-primary">[{{ row.category.name }}]</span>
                  {{ row.subject }} ({{ row.comments_count.toLocaleString() }})
                </a>
             </li>
          </template>
          <li v-else>
            등록된 글이 없습니다.
          </li>
        </ul>

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
    var newURL = window.location.protocol + "//" + window.location.host + "/insurances"; 

    return {
      base: newURL,
      rows: [],
      perpage: 15,
      page: 0,
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
    var getDataURL = window.location.pathname;
    
    this.$http.get(getDataURL+'/jisiklist')
    .then((response) => {
      this.rows = response.data
    })   
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