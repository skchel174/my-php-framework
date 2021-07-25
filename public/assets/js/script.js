function toggleNavLinks() {
    const url = window.location;
    const links = document.querySelector('.nav').querySelectorAll('a');
    links.forEach(link => {
        link.style.color = (link.href === url.pathname || link.href === url.href)
            ? link.classList.add('nav__a_active')
            : link.classList.remove('nav__a_active');
    });
}

toggleNavLinks();