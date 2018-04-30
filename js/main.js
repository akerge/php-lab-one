$(function () {
    $('form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/mywebapp/application/database/registerHandler.php',
            data: $('form').serialize(),
            success: function (response) {
                console.log(response);
                alert('form was submitted');
            }
        });
    });
});