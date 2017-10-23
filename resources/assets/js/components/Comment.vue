<template>
    <div class="comment">
        <div class='comment-title'>
            <div class="pull-right small" v-if="status == 'none'">
                <a href="reply" @click.prevent="status = 'reply'"><i class="fa fa-comment" aria-hidden="true"></i> 댓글</a>
                <template v-if="comment.user_id == user">
                    <span class="text-muted">&#x2223</span>
                    <a href="#" @click.prevent="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</a>
                    <span class="text-muted">&#x2223</span>
                    <a href="#" @click.prevent="remove(comment.id)"><i class="fa fa-trash" aria-hidden="true"></i> 삭제</a>
                </template>
            </div>
            <strong>{{ comment.user.name }}</strong>
            <small class="text-muted" :title="comment.created_at">{{ dateAt(comment.created_at) }}</small>
        </div>
        <div class="comment-body" v-if="status != 'edit'" v-html="comment.content"></div>

        <div v-if="status == 'edit'">
            <textarea class="form-control input-sm" rows="3" v-model="content" @keyup.esc="cancel"></textarea>
            <div class="text-right">
                <button class="btn btn-sm btn-primary" @click="update">수정</button>
                <button class="btn btn-sm btn-default" @click="cancel">취소</button>
            </div>
        </div>

        <ul class="reply">
            <li v-for="childen in comment.children" :key="childen.id">
                <div class='comment-title'>
                    <div class="pull-right small" v-if="childen.user_id == user">
                        <a href="#" @click="remove(childen.id)"><i class="fa fa-trash" aria-hidden="true"></i> 삭제</a>
                    </div>
                    <strong>{{ childen.user.name }}</strong>
                    <small class="text-muted" :title="childen.created_at">{{ dateAt(childen.created_at) }}</small>
                </div>
                <div v-html="childen.content"></div>
            </li>
            <li v-if="status == 'reply'">
                <textarea id="reply-content" class="form-control input-sm" rows="3" @keyup.esc="cancel"></textarea>
                <div class="text-right">
                    <button class="btn btn-sm btn-primary" @click="reply">등록</button>
                    <button class="btn btn-sm btn-default" @click="cancel">취소</button>
                </div>
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
        methods: {
            dateAt: function(date) {
                let now = this.moment();
                let d = this.moment(date);

                if (now.diff(d, 'days', true) < 1) {
                    return d.format('LT');
                } else {
                    return d.format('LL');
                }
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
                        });
                }
            },
            reply: function() {
                let url = window.location.pathname+'/comments/'+this.comment.id+'/reply';
                let content = document.getElementById('reply-content').value.trim();
                console.log(content);
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
    .comment {
        padding: 0 0.5em 0.5em 0.5em;
        margin-bottom: 0.5em;
        border-bottom: 1px dashed #cccccc;

        .comment-title {
            margin-bottom: 0.5em;

            small.text-muted {
                margin-left: 1em;
            }
        }
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
