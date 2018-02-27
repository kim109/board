<template>
  <div class="summary-card">
    <div class="row no-gutters">
      <div class="col p-4">
        <ul class="list-unstyled">
          <li v-for="article in articles" :key="article.id">
            <a :href="'/'+article.board+'/'+article.id">
              <span class="text-primary">[{{ article.category.name }}]</span>
              {{ article.subject }}
              <span v-if="type == 'home'">- {{ maskName(article.user.name) }}</span>
              <span v-else>({{ article.comments_count.toLocaleString() }})</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="d-none d-lg-block">
        <img src="/images/summary-right.jpg" height="225">
      </div>
    </div>
  </div>
</template>

<script>
if (!String.prototype.repeat) {
  String.prototype.repeat = function(count) {
    'use strict'
    if (this == null) {
      throw new TypeError('can\'t convert ' + this + ' to object')
    }
    var str = '' + this
    count = +count
    if (count != count) {
      count = 0
    }
    if (count < 0) {
      throw new RangeError('repeat count must be non-negative')
    }
    if (count == Infinity) {
      throw new RangeError('repeat count must be less than infinity')
    }
    count = Math.floor(count);
    if (str.length == 0 || count == 0) {
      return ''
    }
    // Ensuring count is a 31-bit integer allows us to heavily optimize the
    // main part. But anyway, most current (August 2014) browsers can't handle
    // strings 1 << 28 chars or longer, so:
    if (str.length * count >= 1 << 28) {
      throw new RangeError('repeat count must not overflow maximum string size')
    }
    var rpt = ''
    for (var i = 0; i < count; i++) {
      rpt += str
    }
    return rpt
  }
}

export default {
  props: {
    type: {
      type: String,
      required: true
    },
  },
  data: function () {
    return {
      articles: null
    }
  },
  beforeMount: function() {
    this.$http.get('/'+this.type+'/popularity')
    .then((response) => {
      this.articles = _.orderBy(response.data, ['hits'], ['desc'])
    })
  },
  methods: {
    maskName: function(name) {
      let masking = name.charAt(0)

      if (name.length == 2) {
        masking = masking+'*'
      } else if (name.length > 2) {
        masking = masking + '*'.repeat(name.length-2) + name.substr(-1)
      }

      return masking
    }
  }
}
</script>

<style lang="scss" scoped>
  .summary-card {
    border: 5px solid #148fed;
    background: white;

    ul {
      margin-bottom: 0;

      li {
        margin-bottom: 0.5rem;
        &:last-child {
          margin-bottom: 0;
        }

        a {
          text-decoration: none;
          color: black;
          &:hover {
            font-weight: bold;
          }
        }
      }
    }
  }
</style>
