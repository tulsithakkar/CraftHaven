/// Tulsi Thakkar
document.addEventListener('DOMContentLoaded', (event) => {
    let profileform = document.querySelector('.Profile');
    let searchForm = document.querySelector('.search-form');
    let shoppingcart = document.querySelector('.shopping-cart');
    let loginForm = document.querySelector('.login-form');
    let registerForm = document.querySelector('.login-form1');
    //var userLoginSession = " . (isset($_SESSION['userlogin']) && $_SESSION['userlogin'] ? 'true' : 'false') . ";




    document.querySelector('#Profile-btn').onclick = () => 
    {
        if (userLoginSession === false) {
            loginForm.classList.toggle('active');
            profileform.classList.remove('active');
           searchForm.classList.remove('active');
           shoppingcart.classList.remove('active');
           registerForm.classList.remove('active');
        } else {
            
            loginForm.classList.remove('active');
            shoppingcart.classList.remove('active');
            searchForm.classList.remove('active');
            profileform.classList.toggle('active');
            registerForm.classList.remove('active');
        }



       
    }
    document.querySelector('#search-btn').onclick = () => {
        searchForm.classList.toggle('active');
        profileform.classList.remove('active');
        shoppingcart.classList.remove('active');
        loginForm.classList.remove('active');
        registerForm.classList.remove('active');
    };
    document.querySelector('#home-btn').onclick = () => {
        window.location.href = 'index.php';
    };
    document.querySelector('#cart-btn').onclick = () => {
        if (userLoginSession === false) {
            alert('Please log in to contioue..');
            loginForm.classList.toggle('active');
            profileform.classList.remove('active');
           searchForm.classList.remove('active');
           registerForm.classList.remove('active');
           shoppingcart.classList.remove('active');

          
        } else {
            shoppingcart.classList.toggle('active');
            searchForm.classList.remove('active');
            profileform.classList.remove('active');
            loginForm.classList.remove('active');
            registerForm.classList.remove('active');
        }


        document.getElementById('loginlink').addEventListener('click', (event) => {
            event.preventDefault();
            registerForm.classList.remove('active');
            loginForm.classList.toggle('active');
            searchForm.classList.remove('active');
            shoppingcart.classList.remove('active');
            navbar.classList.remove('active');
            profileform.classList.remove('active');
        });

        document.getElementById('Reglink').addEventListener('click', (event) => {
            event.preventDefault();
            registerForm.classList.toggle('active');
            loginForm.classList.remove('active');
            searchForm.classList.remove('active');
            shoppingcart.classList.remove('active');
            navbar.classList.remove('active');
            profileform.classList.remove('active');
        });

      
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
});