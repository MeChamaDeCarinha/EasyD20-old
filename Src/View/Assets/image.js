var img;
var select;
var preview;
var reader = new FileReader();

window.onload = (function () {
    img = document.querySelector('img').src;
    select = document.getElementById('imagem');
    preview = document.querySelector('img');
});

function previewImage() {
    var file = document.querySelector('input[type=file]').files[0];

    reader.onloadend = function () {
        preview.src = reader.result;
        select.value = "file";
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}

function changeImg(img_type) {
    if (select.value != "none") {
        preview.src = "../../../../Src/View/Images/" + img_type + "/" + select.value;
    }
    else {
        preview.src = img;
    }
}