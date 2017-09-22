<template>
    <div class="comment">
        <div class='comment-title'>
            <div class="pull-right small" v-if="status == 'none'">
                <span  @click="status = 'reply'"><i class="fa fa-comment" aria-hidden="true"></i> 댓글</span>
                <template v-if="comment.user_id == user">
                    <span class="text-muted">&#x2223</span>
                    <span @click="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</span>
                    <span class="text-muted">&#x2223</span>
                    <span @click="remove(comment.id)"><i class="fa fa-trash" aria-hidden="true"></i> 삭제</span>
                </template>
            </div>
            <strong>{{ comment.user.name }}</strong>
            <small class="text-muted">{{ this.date }}</small>
        </div>
        <div class="comment-body" v-if="status != 'edit'" v-html="comment.content"></div>

        <div class="row" v-if="status == 'edit'">
            <div class="col-sm-10 col-md-11">
                <textarea class="form-control input-sm" rows="3" v-model="content" @keyup.esc="cancel"></textarea>
            </div>
            <div class="col-sm-2 col-md-1">
                <input class="btn btn-primary comment-submit" type="submit" value="수정" @click="update">
            </div>
        </div>

        <ul class="reply">
            <li v-for="childen in comment.childs">
                <div class='comment-title'>
                    <div class="pull-right small" v-if="childen.user_id == user">
                        <span @click="remove(childen.id)"><i class="fa fa-trash" aria-hidden="true"></i> 삭제</span>
                    </div>
                    <strong>{{ childen.user.name }}</strong>
                    <small class="text-muted">{{ new Date(childen.created_at).toISOString() }}</small>
                </div>
                <div v-html="childen.content"></div>
            </li>
            <li v-if="status == 'reply'">
                <textarea id="reply-content" class="form-control input-sm" rows="3" @keyup.esc="cancel"></textarea>
                <input class="btn btn-primary comment-submit" type="submit" value="등록" @click="reply">
            </li>
        </ul>
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
        computed: {
            date: function() {
                let date = new Date(this.comment.created_at);
                // return date.toLocaleDateString() + ' ' + date.toLocaleTimeString('ko-KR');
                return date.toISOString();
            }
        },
        methods: {
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
                        });
                }
            },
            reply: function() {
                let url = window.location.pathname+'/comments/'+this.comment.id+'/reply';
                let content = document.getElementById('reply-content').value;
                this.$http.post(url, { 'content': content })
                    .then((response) => {
                            this.status = 'none';
                            this.$emit('reload');
                    });
            }
        }
    }
</script>

<style lang="scss" scoped>
    small.text-muted {
        margin-left: 1em;
    }

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
