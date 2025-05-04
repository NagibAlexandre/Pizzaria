const toggleTheme = document.getElementById('toggleTheme');
const themeIcon = document.getElementById('themeIcon');
const siteLogo = document.getElementById('siteLogo');

const path = window.location.pathname;

const depth = path.split('/').filter(p => p).length;

let baseLogoPath;
if (depth >= 3) {
    baseLogoPath = '../../images';
} else {
    baseLogoPath = '../images';
}

const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
    siteLogo.src = `${baseLogoPath}/LogoDark.png`;
} else {
    siteLogo.src = `${baseLogoPath}/LogoLight.png`;
}

toggleTheme.addEventListener('click', function (e) {
    e.preventDefault();
    document.body.classList.toggle('dark-mode');

    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    themeIcon.classList.toggle('bi-sun-fill', !isDark);
    themeIcon.classList.toggle('bi-moon-fill', isDark);
    siteLogo.src = isDark ? `${baseLogoPath}/LogoDark.png` : `${baseLogoPath}/LogoLight.png`;
});
