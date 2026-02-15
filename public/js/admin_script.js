const navbar = document.querySelector('.header .flex .navbar');
const profile = document.querySelector('.header .flex .profile');
const menuBtn = document.querySelector('#menu-btn');
const userBtn = document.querySelector('#user-btn');

if (menuBtn && navbar && profile) {
   menuBtn.onclick = () => {
      navbar.classList.toggle('active');
      profile.classList.remove('active');
   };
}

if (userBtn && navbar && profile) {
   userBtn.onclick = () => {
      profile.classList.toggle('active');
      navbar.classList.remove('active');
   };
}

if (navbar && profile) {
   window.onscroll = () => {
      navbar.classList.remove('active');
      profile.classList.remove('active');
   };
}

const mainImage = document.querySelector('.update-product .image-container .main-image img');
const subImages = document.querySelectorAll('.update-product .image-container .sub-image img');

if (mainImage && subImages.length) {
   subImages.forEach((image) => {
      image.onclick = () => {
         const src = image.getAttribute('src');
         if (src) {
            mainImage.src = src;
         }
      };
   });
}
