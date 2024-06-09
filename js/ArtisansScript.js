/// Tulsi Thakkar
document.getElementById('togglepassword').addEventListener('click', function() {
    var passwordInput = document.getElementById('ArtisanPassword');
   
    var icon = document.getElementById('togglepassword');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

document.getElementById('togglepassword1').addEventListener('click', function() {
    var passwordInput = document.getElementById('ArtisanConformPassword');
   
    var icon = document.getElementById('togglepassword1');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});