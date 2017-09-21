<template>
    <div class="comment">
        <div class='comment-title'>
            <div class="pull-right small" v-if="status != 'edit'">
                <span><i class="fa fa-comment" aria-hidden="true"></i> 댓글</span>
                <template v-if="comment.owner">
                    <span class="text-muted">&#x2223</span>
                    <span @click="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</span>
                    <span class="text-muted">&#x2223</span>
                    <span @click="remove"><i class="fa fa-trash" aria-hidden="true"></i> 삭제</span>
                </template>
            </div>
            <strong>{{ comment.author }}</strong>
            <small class="text-muted">{{ this.date }}</small>
        </div>
        <div class="comment-body" v-if="status != 'edit'" v-html="comment.content"></div>
        <div class="row" v-if="status == 'edit'">
            <div class="col-sm-10 col-md-11">
                <textarea class="form-control input-sm" rows="2" v-model="content" @keyup.esc="cancel"></textarea>
            </div>
            <div class="col-sm-2 col-md-1">
                <input class="btn btn-primary comment-submit" type="submit" value="수정" @click="update">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            comment: {
                type: Object,
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
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString('ko-KR');
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
                $.ajax({
                    url: window.location.pathname+'/comments/'+this.comment.id,
                    method: "PATCH",
                    data: {
                        'content': this.content
                    }
                })
                .done((data) => {
                    this.comment.content = this.content.replace(/\n/g, '<br />');
                    this.status = 'none';
                    this.content = null;
                });
            },
            remove: function() {
                if (confirm('댓글을 삭제하시겠습니까?')) {
                    $.ajax({
                        url: window.location.pathname+'/comments/'+this.comment.id,
                        method: "DELETE"
                    })
                    .done((data) => {
                        this.$emit('reload');
                    });
                }
            }
        }
    }
</script>

<style scoped>
    small.text-muted {
        margin-left: 1em;
    }
</style>
