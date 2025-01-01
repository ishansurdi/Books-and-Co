// script.js
document.getElementById('menu-icon').addEventListener('click', function() {
    document.getElementById('side-menu').classList.add('active');
    document.getElementById('overlay').classList.add('active');
});

document.getElementById('close-btn').addEventListener('click', function() {
    document.getElementById('side-menu').classList.remove('active');
    document.getElementById('overlay').classList.remove('active');
});
