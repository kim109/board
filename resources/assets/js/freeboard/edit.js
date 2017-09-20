require('../bootstrap');
require('../wysiwyg');

$(document).ready(function() {
    $('.attachment-delete').click(function(e) {
        e.preventDefault();

        let url = $(this).attr('href');
        $.ajax({
            method: "DELETE",
            url: url
        })
        .done(function(data, textStatus) {
            location.reload(true);
        });
    });
});