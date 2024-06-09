/// Tulsi Thakkar
let searchForm = document.querySelector('.search-form');
let loginForm = document.querySelector('.login-form');
let registerForm = document.querySelector('.login-form1');
let shoppingcart = document.querySelector('.shopping-cart');
let navbar = document.querySelector('.navbar');
let profileform = document.querySelector('.Profile');
var userLoginSession = " . (isset($_SESSION['userlogin']) && $_SESSION['userlogin'] ? 'true' : 'false') . ";



document.querySelector('#search-btn').onclick = () => {
    searchForm.classList.toggle('active');
    profileform.classList.remove('active');
    shoppingcart.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
    registerForm.classList.remove('active');
};


document.querySelector('#cart-btn').onclick = () => {
    if (userLoginSession === false) {
        alert('Please log in to contioue..');
        registerForm.classList.remove('active');
        loginForm.classList.toggle('active');
        searchForm.classList.remove('active');
        shoppingcart.classList.remove('active');
        navbar.classList.remove('active');
        profileform.classList.remove('active');
    } else {
        profileform.classList.remove('active');
        registerForm.classList.remove('active');
        loginForm.classList.remove('active');
        searchForm.classList.remove('active');
        shoppingcart.classList.toggle('active');
        navbar.classList.remove('active');
    }
};



document.querySelector('#login-btn').onclick = () => {
    
    if (userLoginSession === false) {
        registerForm.classList.remove('active');
        loginForm.classList.toggle('active');
        searchForm.classList.remove('active');
        shoppingcart.classList.remove('active');
        navbar.classList.remove('active');
        profileform.classList.remove('active');
    } else {
        profileform.classList.toggle('active');
        registerForm.classList.remove('active');
        loginForm.classList.remove('active');
        searchForm.classList.remove('active');
        shoppingcart.classList.remove('active');
        navbar.classList.remove('active');
    }
};


document.querySelector('#menu-btn').onclick = () => {
 
    navbar.classList.toggle('active');
    searchForm.classList.remove('active');
    shoppingcart.classList.remove('active');
    loginForm.classList.remove('active');
    profileform.classList.remove('active');
    registerForm.classList.remove('active');
};


var swiper = new Swiper(".crafts-slider", {
    loop:true,
    spaceBetween: 20,
    autoplay:{
        delay:4500,
        disableOnInteraction:false,
    },
    breakpoints:{
        0:{
            slidesPerView: 1,
        },
        768:
        {
            slidesPerView: 2,   
        },
        1020:
        {
            slidesPerView: 3,  
        }
    }

    
  });

window.onscroll = () => {
    searchForm.classList.remove('active');
    shoppingcart.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
    profile.classList.remove('active');
    registerForm.classList.remove('active');
};


document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('loginlink').addEventListener('click', (event) => {
        event.preventDefault();
        registerForm.classList.remove('active');
        loginForm.classList.toggle('active');
        searchForm.classList.remove('active');
        shoppingcart.classList.remove('active');
        navbar.classList.remove('active');
        profileform.classList.remove('active');
    });
});


document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('Reglink').addEventListener('click', (event) => {
        event.preventDefault();
        registerForm.classList.toggle('active');
        loginForm.classList.remove('active');
        searchForm.classList.remove('active');
        shoppingcart.classList.remove('active');
        navbar.classList.remove('active');
        profileform.classList.remove('active');
    });
});