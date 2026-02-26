let navbar = document.querySelector('.header .navbar');
let profile = document.querySelector('.header .flex .profile');

var menuBtn = document.querySelector('#menu-btn');
if (menuBtn) {
   menuBtn.onclick = () => {
      if (navbar) navbar.classList.toggle('active');
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
      var cd = document.getElementById('cat-dropdown');
      if (cd) cd.classList.remove('open');
   }
}

window.onscroll = () => {
   if (navbar) navbar.classList.remove('active');
   if (profile) profile.classList.remove('active');
   var cd = document.getElementById('cat-dropdown');
   if (cd) cd.classList.remove('open');
}

let mainImage = document.querySelector('.quick-view .box .row .image-container .main-image img');
let subImages = document.querySelectorAll('.quick-view .box .row .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});