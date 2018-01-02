require('./bootstrap')
require('./wysiwyg')

window.Dropzone = require('dropzone')
Dropzone.autoDiscover = false

$(document).ready(() => {
    let myDropzone = new Dropzone("#attachment", {
        url: "/attachments",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 3,
        maxFilesize: 5,
        addRemoveLinks: true,
        dictRemoveFile: '삭제'
    })
    .on('success', (file, data) => {
        file._id = data.id;
        file._name = data.name;

        $("<input>", {
            type: "hidden",
            name: "attachments[]",
            value: data.id
        }).appendTo($('form#fm'));
    })
    .on('removedfile', (file, data) => {
        $('input[type=hidden][value='+file._id+']').remove()
        axios.delete('/attachments/'+file._id)
    })
    .on('maxfilesexceeded', (file) => {
        alert ("최대 3개의 파일을 첨부할수 있습니다.")
        this.removeFile(file)
    })
})