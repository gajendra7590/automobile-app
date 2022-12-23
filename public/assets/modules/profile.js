$(document).ready(function () {
    $('#input-profile').change(function (){
        readURL(this);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#profile-change').attr('src', e.target.result);
                $('.img-circle').attr('src', e.target.result);
                $('.user-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
});

