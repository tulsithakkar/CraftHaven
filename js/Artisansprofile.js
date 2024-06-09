/// Tulsi Thakkar
let Profile = document.querySelector('.Profile');
let EditForm = document.querySelector('.Edit-form');
document.querySelector('#Profile-btn').onclick = () => {
    Profile.classList.toggle('active');
    EditForm.classList.remove('active');
};

document.querySelector("#home-btn").onclick = () =>
{
    window.open('index.php','_self');
}


const addProductBtn = document.getElementById('addProductBtn');
const cancelAddbtn = document.getElementById('cancelAddbtn');
const addProductForm = document.getElementById('Product_form');

const overlay = document.getElementById('overlay');

addProductBtn.addEventListener('click', (e) => {
    e.preventDefault();
    addProductForm.style.display = 'block';
    overlay.style.display = 'block';
});

cancelAddbtn.addEventListener(
    'click', (e) => {
        e.preventDefault(); // Temporarily disable validation
        const formElements = addProductForm.elements;
        for (let i = 0; i < formElements.length; i++) {
            formElements[i].removeAttribute('required');
        }
        addProductForm.style.display = 'none';
        overlay.style.display = 'none';
    }
);


overlay.addEventListener('click', () => {
   // addProductForm.style.display = 'none';
    //overlay.style.display = 'none';
});



