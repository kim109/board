require('../bootstrap')
require('../wysiwyg')

window.Dropzone = require('dropzone')
Dropzone.autoDiscover = false

$(document).ready(() => {
  // 썸네일
  new Dropzone("#thumbnail", {
    url: "/attachments",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    acceptedFiles: "image/*",
    maxFiles: 1,
    maxFilesize: 5,
    addRemoveLinks: true,
    dictRemoveFile: '삭제'
  })
  .on('success', (file, data) => {
    file._id = data.id
    file._name = data.name

    $("input[name=thumbnail_id]").val(data.id)
    $("<input>", {
        type: "hidden",
        name: "attachments[]",
        value: data.id
    }).appendTo($('form#fm'));
  })
  .on('removedfile', (file, data) => {
    $('input[name^=attachments][value='+file._id+']').remove()
    $("input[name=thumbnail_id]").val('')
    axios.delete('/attachments/'+file._id)
  })
  .on('maxfilesexceeded', (file) => {
    alert ("최대 3개의 파일을 첨부할수 있습니다.")
    this.removeFile(file)
  })

  // 첨부파일
  new Dropzone("#attachment", {
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

  $('#fm').submit((event) => {
    if ($("input[name=thumbnail_id]").val().length == 0) {
      alert('썸네일 이미지를 등록해주세요.')
      return false;
    }
    return confirm('작성된 내용을 저장하시겠습니까?')
  })
})