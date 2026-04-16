

"use strict";

// $(document).ready(function() {
//     $('.js-example-basic-single').select2();
// });

// $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup', function() {
//     let pass = $("#exampleInputPassword").val();
//     let passRepeat = $("#exampleRepeatPassword").val();
//     if (pass === passRepeat) {
//         $('.pass').hide();
//     } else {
//         $('.pass').show();
//     }
// });


function readURL(input, viewer) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $('#' + viewer).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}




$("#customFileEg1").change(function() {
    readURL(this, 'logoImageViewer');
});

$("#coverImageUpload").change(function() {
    readURL(this, 'coverImageViewer');
});

$(".lang_link").click(function(e){
    e.preventDefault();
    $(".lang_link").removeClass('active');
    $(".lang_form").addClass('d-none');
    $(this).addClass('active');
    let form_id = this.id;
    let lang = form_id.substring(0, form_id.length - 5);
    $("#"+lang+"-form").removeClass('d-none');

});
