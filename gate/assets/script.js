function showProgress(e) {
    var one = document.getElementById('progress_one');
    var two = document.getElementById('progress_two');
    var load = JSON.parse(e);
    if (load.status = "success") {
        if (load.data.cpu > load.data.mem) {
            one.style.width = load.data.mem + "%";
            one.setAttribute('class', 'progress-bar bg-success');
            two.style.width = (load.data.cpu - load.data.mem) + "%";
            two.setAttribute('class', 'progress-bar bg-danger');
        } else {
            one.style.width = load.data.cpu + "%";
            one.setAttribute('class', 'progress-bar bg-danger');
            two.style.width = (load.data.mem - load.data.cpu) + "%";
            two.setAttribute('class', 'progress-bar bg-success');
        }
    }
}

function load(callback, url, method = "GET") {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (xhr.readyState == 4 && xhr.status == 200){
            callback(xhr.responseText);
        }
    }
    xhr.open(method, url, true);
    xhr.send();
}

if (document.getElementById('progress_one') && document.getElementById('progress_two')) {
    load(showProgress, "?api=load")
    setInterval(function() {load(showProgress, "?api=load")}, 3000);
}

$('#modal-closeup').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var link = button.data('imagelink')
    var modal = $(this)
    modal.find('#image-closeup').attr('src', link)
})

$(document).on("click", ".passImageInfo", function () {
    var id = $(this).attr('src');
    $(".modal-body").children('img').attr('src', id)
    $('#image-closeup').modal('handleUpdate')
});