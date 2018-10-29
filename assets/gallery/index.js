import Jimp from 'jimp';
import axios from 'axios';
import 'form-serializer';
$(document).ready(function(){
    $(document).on("click", "#camera-btn", function(){
        $("#camera-modal :input").val("");
        $("#camera-input").val("");
        $("#resize-img").attr("src", "#");
        $("#camera-input").trigger("click");
    });

    $("#camera-input").on("change", function(){
        // Downscale the image
        let input = $("#camera-input");
        let file = $(input)[0].files[0];
        if( !(/image/i).test( file.type ) ) {
            alert( "File "+ file.name +" is not an image." );
        } else {
            let reader = new FileReader();
            reader.readAsArrayBuffer(file);
            reader.onload = function (event) {
                Jimp.read(event.target.result)
                    .then(img => {
                        let container = $("#resize-container");
                        let mHeight = container.data("m-height");
                        let mWidth = container.data("m-width");
                        let ratio = 0;
                        if (img.getWidth() > img.getHeight()) {
                            ratio = mWidth / img.getWidth();
                        } else {
                            ratio = mHeight / img.getHeight();
                        }
                        img.resize(img.getWidth() * ratio, img.getHeight() * ratio).getBase64(Jimp.MIME_PNG, function (err, src) {
                            let el = $("#resize-img");
                            let input = $("[name='base64_file']");
                            input.val(src);
                            el.attr('src', src);
                        });
                    })
            };
            $("#camera-modal").modal();
        }
    });

    $(document).on("submit", "form[data-ajax-url]", function(evt) {
        evt.preventDefault();
        let btn = $(evt.target).find("[type='submit']").prop('disabled', true);
        let url = $(evt.target).data("ajax-url");
        let data = $(evt.target).serializeObject();
        axios({
            method: "POST",
            url: url,
            data: data
        }).then(function(response){
            location.reload()
        }).catch(function(response){
            console.log(response);
        }).finally(function(){
            btn.prop('disabled', false);
        });
        return false;
    })
});