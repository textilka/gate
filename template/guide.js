$(document).on("click", ".myShit", function () {
    var id = $(this).attr('src');
    $(".modal-body").children('img').attr('src', id)
    $('#image-closeup').modal('handleUpdate')
});