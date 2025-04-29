const startAnimation = (entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            if (entry.target.classList.contains('slide-in-left')) {
                entry.target.classList.add('is-visible');
            } else if (entry.target.classList.contains('slide-in-right')) {
                entry.target.classList.add('is-visible');
            }
            // Stop observing the element after the animation is triggered
            observer.unobserve(entry.target);
        }
    });
};

const observer = new IntersectionObserver(startAnimation, { root: null, rootMargin: '0px', threshold: 0.1 });

const elements = document.querySelectorAll('.slide-in-left', '.slide-in-right');
elements.forEach(el => {
    observer.observe(el);
});
