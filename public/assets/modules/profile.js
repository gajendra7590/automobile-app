$(document).ready(function () {
    $('#input-profile').change(function (e){
        $('#profile-change').attr('src', e.target.result)
    });
});

