//bg-info
//bg-success

function load(url, method = "GET") {
    return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.state == 200){
                resolve(xhr.responseText);
            }
        }
        xhr.open(method, url, true);
        xhr.send();
    });
}

if (document.getElementById('progress_one') && document.getElementById('progress_two')) {
    load("?api=load").then(function(e) {
        var one = document.getElementById('progress_one');
        var two = document.getElementById('progress_two');
        var data = JSON.parse(e);
        if (data.status = "success") {
            if (data.cpu > data.mem) {
                // mem first
                one.style.width = data.mem + "%";
                one.setAttribute('class', 'progress-bar bg-success');
                two.style.width = (data.cpu - data.mem) + "%";
                two.setAttribute('class', 'progress-bar bg-info');
            } else {
                // cpu first
                one.style.width = data.cpu + "%";
                one.setAttribute('class', 'progress-bar bg-info');
                two.style.width = (data.mem - data.cpu) + "%";
                two.setAttribute('class', 'progress-bar bg-success');
            }
        }
    });
}
