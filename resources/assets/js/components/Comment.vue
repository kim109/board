<template>
  <div class="comment py-2">
    <div class="row mb-2">
      <div class="col-sm-6">
        {{ maskName(comment.user.name) }} <span class="text-muted">( {{ comment.user.user_id.replace(/.{4}$/, '****') }} )</span>
        <small class="text-muted ml-3" :title="comment.created_at">{{ dateAt(comment.created_at) }}</small>
      </div>
      <div class="col-sm-6 text-right small" v-if="status == 'none'">
        <a href="reply" @click.prevent="status = 'reply'"><i class="fas fa-reply" aria-hidden="true"></i> 댓글</a>
        <template v-if="comment.user_id == user">
            <span class="text-muted">&#x2223;</span>
            <a href="#" @click.prevent="edit"><i class="fas fa-edit" aria-hidden="true"></i> 수정</a>
            <span class="text-muted">&#x2223;</span>
            <a href="#" @click.prevent="remove(comment.id)"><i class="fas fa-trash" aria-hidden="true"></i> 삭제</a>
        </template>
      </div>
    </div>

    <div class="comment-body" v-if="status != 'edit'" v-html="comment.content"></div>
    <div v-if="status == 'edit'">
      <textarea class="form-control form-control-sm mb-1" rows="3" v-model="content" @keyup.esc="cancel"></textarea>
      <div class="text-right">
        <button class="btn btn-sm btn-primary" @click="update">수정</button>
        <button class="btn btn-sm btn-default" @click="cancel">취소</button>
      </div>
    </div>

    <ul class="reply">
      <li v-for="childen in comment.children" :key="childen.id">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{ maskName(childen.user.name) }} <span class="text-muted">( {{ childen.user.user_id.replace(/.{4}$/, '****') }} )</span>
                <small class="text-muted ml-3" :title="comment.created_at">{{ dateAt(childen.created_at) }}</small>
            </div>
            <div class="col-sm-6 text-right small" v-if="childen.user_id == user">
              <a href="#" @click="remove(childen.id)"><i class="fa fa-trash" aria-hidden="true"></i> 삭제</a>
            </div>
        </div>
        <div v-html="childen.content"></div>
      </li>
      <li v-if="status == 'reply'">
        <textarea id="reply-content" class="form-control form-control-sm mb-1" rows="3" @keyup.esc="cancel"></textarea>
        <div class="text-right">
            <button class="btn btn-sm btn-primary" @click="reply">등록</button>
            <button class="btn btn-sm btn-default" @click="cancel">취소</button>
        </div>
      </li>
    </ul>

    <hr>
  </div>
</template>

<script>
  export default {
    props: {
      comment: {
        type: Object,
        required: true
      },
      user: {
        type: Number,
        required: true
      }
    },
    data: function () {
      return {
        status: "none",
        content: null
      }
    },
    methods: {
      dateAt: function(date) {
        let now = this.moment()
        let d = this.moment(date)

        return (now.diff(d, 'days', true) < 1) ? d.format('LT') : d.format('LL')
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
      edit: function() {
        this.status = 'edit';
        this.content = this.comment.content.replace(/<br ?\/?>/g,"\n");
      },
      cancel: function() {
        this.status = 'none';
        this.content = null;
      },
      update: function() {
        let url = window.location.pathname+'/comments/'+this.comment.id;
        this.$http.patch(url, { 'content': this.content });

        this.comment.content = this.content.replace(/\n/g, '<br />');
        this.status = 'none';
        this.content = null;
      },
      remove: function(id) {
        if (confirm('댓글을 삭제하시겠습니까?')) {
          let url = window.location.pathname+'/comments/'+id;
          this.$http.delete(url)
              .then((response) => {
                this.$emit('reload');
              })
        }
      },
      reply: function() {
        let url = window.location.pathname+'/comments/'+this.comment.id+'/reply';
        let content = document.getElementById('reply-content').value.trim();

        if (content.length > 0) {
          this.$http.post(url, { 'content': content })
              .then((response) => {
                this.status = 'none';
                this.$emit('reload');
              });
        } else {
          alert('내용을 입력해주세요.');
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  ul.reply {
    list-style: none;
    border-left: 3px solid #e0e0e0;
    margin: 0.5em 0;
    padding-left: 1em;

    li {
      padding: 0.5em 0;
      border-bottom: 1px dashed #cccccc;

      &:last-child {
        border-bottom: none;
      }
    }
  }
</style>
