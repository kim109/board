require('./bootstrap');

$(document).ready(function() {
    $('.comment-delete').click(function (e) {
        e.preventDefault();

        let url = $(this).attr('href');
        $.confirm({
            title: '댓글 삭제',
            content: '해당 댓글를 삭제 하시겠습니까?',
            buttons: {
                '삭제': {
                    btnClass: 'btn-danger',
                    action: function() {
                        $.ajax({
                            method: "DELETE",
                            url: url
                        })
                        .done(function(data, textStatus) {
                            location.reload(true);
                        });
                    }
                },
                '취소': function() {}
            }
        });
    });
});