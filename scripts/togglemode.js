const toggleTheme = document.getElementById('toggleTheme');
const themeIcon = document.getElementById('themeIcon');

const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
}

toggleTheme.addEventListener('click', function (e) {
    e.preventDefault();
    document.body.classList.toggle('dark-mode');

    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    themeIcon.classList.toggle('bi-sun-fill', !isDark);
    themeIcon.classList.toggle('bi-moon-fill', isDark);
});