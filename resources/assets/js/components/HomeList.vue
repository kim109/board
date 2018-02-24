<template>
  <div class="home-list">
    <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
          <a class="nav-link" :class="{active : mode == 'qna'}" href="#" @click.prevent="mode='qna'">치카지식인</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" :class="{active : mode == 'seminars'}" href="#" @click.prevent="mode='seminars'">세미나소식</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" :class="{active : mode == 'notices'}" href="#" @click.prevent="mode='notices'">공지사항</a>
        </li>
      </ul>
      <div class="p-3 contents">
        <ul class="list-unstyled">
          <li v-for="list in lists" :key="'s-'+list.id" class="mb-2">
            <a :href="'/'+mode+'/'+list.id">
              <span class="text-primary">[{{ list.category.name }}]</span>
              {{ list.subject }} ({{ list.comments_count.toLocaleString() }})
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
      mode: 'qna',
      lists: []
    }
  },
  watch: {
    mode: function () {
      this.getData()
    }
  },
  beforeMount: function () {
    this.getData()
  },
  methods: {
    getData: function() {
      this.$http.get('/home/list', {
        params: { mode: this.mode }
      })
      .then((response) => {
        this.lists = response.data
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .home-list {
    background-color: white;

    .nav {
      border: 1px solid #cdcdcd;

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

    .contents {
      border: 1px solid #cdcdcd;
      border-top: none;

      a {
        color: black;
        text-decoration: none;
      }
    }
  }
</style>