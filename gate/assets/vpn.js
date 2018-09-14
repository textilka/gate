$('#modal-closeup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('imagelink')
    var modal = $(this)
    modal.find('.image-closeup').src(recipient)
})
