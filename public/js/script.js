let navbar = document.querySelector('.header .navbar');
let profile = document.querySelector('.header .flex .profile');
let navLeft = document.querySelector('.header .nav-left');

var menuBtn = document.querySelector('#menu-btn');
if (menuBtn) {
   menuBtn.onclick = (e) => {
      if (e) e.stopPropagation();
      const isOpen = navbar ? navbar.classList.toggle('active') : !(navLeft && navLeft.classList.contains('active'));
      if (navLeft) navLeft.classList.toggle('active', !!isOpen);
      if (profile) profile.classList.remove('active');
      var cd = document.getElementById('cat-dropdown');
      if (cd) cd.classList.remove('open');
   }
}

var userBtn = document.querySelector('#user-btn');
if (userBtn) {
   userBtn.onclick = (e) => {
      e.stopPropagation();
      if (profile) profile.classList.toggle('active');
      if (navbar) navbar.classList.remove('active');
      if (navLeft) navLeft.classList.remove('active');
      var cd = document.getElementById('cat-dropdown');
      if (cd) cd.classList.remove('open');
   }
}

window.onscroll = () => {
   if (navbar) navbar.classList.remove('active');
   if (navLeft) navLeft.classList.remove('active');
   if (profile) profile.classList.remove('active');
   var cd = document.getElementById('cat-dropdown');
   if (cd) cd.classList.remove('open');
}

document.addEventListener('click', (e) => {
   if (!navLeft || navLeft.contains(e.target)) return;
   if (navbar) navbar.classList.remove('active');
   navLeft.classList.remove('active');
});

let mainImage = document.querySelector('.quick-view .box .row .image-container .main-image img');
let subImages = document.querySelectorAll('.quick-view .box .row .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});
