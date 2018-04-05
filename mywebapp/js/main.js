$(function () {
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/mywebapp/registerHandler.php',
            data: $('form').serialize(),
            success: function (response) {
                console.log(response);
                alert('Registration was successful');
            }
        });
    });
});
