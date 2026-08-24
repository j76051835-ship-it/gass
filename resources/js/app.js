import "./bootstrap";

const loader = document.querySelector("[data-site-loader]");

if (loader) {
    const isHomeLoader = document.body.classList.contains("home-landing");
    const revealLoader = () => {
        window.setTimeout(() => {
            loader.classList.add("is-done");
            window.setTimeout(() => loader.remove(), 650);
        }, isHomeLoader ? 2400 : 850);
    };

    if (document.readyState === "complete") {
        revealLoader();
    } else {
        window.addEventListener("load", revealLoader, { once: true });
    }
}

const spotlightItems = document.querySelectorAll(".service-card, .review-card");
spotlightItems.forEach((item) => {
    item.addEventListener("pointermove", (event) => {
        const bounds = item.getBoundingClientRect();
        item.style.setProperty("--pointer-x", `${event.clientX - bounds.left}px`);
        item.style.setProperty("--pointer-y", `${event.clientY - bounds.top}px`);
    });
});

document.querySelectorAll("[data-review-delete-form]").forEach((form) => {
    form.addEventListener("submit", (event) => {
        const code = window.prompt("Masukkan kode untuk menghapus ulasan:");

        if (code === null) {
            event.preventDefault();
            return;
        }

        form.querySelector("[data-delete-code]").value = code.trim();
    });
});

const aboutVisual = document.querySelector(".about-visual");

if (aboutVisual && window.matchMedia("(hover: hover)").matches) {
    aboutVisual.addEventListener("pointermove", (event) => {
        const bounds = aboutVisual.getBoundingClientRect();
        const rotateX = ((event.clientY - bounds.top) / bounds.height - 0.5) * -5;
        const rotateY = ((event.clientX - bounds.left) / bounds.width - 0.5) * 7;
        aboutVisual.style.animationPlayState = "paused";
        aboutVisual.style.setProperty("--about-rotate-x", `${rotateX}deg`);
        aboutVisual.style.setProperty("--about-rotate-y", `${rotateY}deg`);
    });

    aboutVisual.addEventListener("pointerleave", () => {
        aboutVisual.style.animationPlayState = "running";
        aboutVisual.style.setProperty("--about-rotate-x", "0deg");
        aboutVisual.style.setProperty("--about-rotate-y", "0deg");
    });
}

const reviewsCarousel = document.querySelector("[data-reviews-carousel]");
const reviewsTrack = reviewsCarousel?.querySelector("[data-reviews-track]");
const reviewCards = reviewsTrack?.querySelectorAll(".review-card");

if (reviewsCarousel && reviewsTrack && reviewCards?.length > 1) {
    let reviewIndex = 0;
    let reviewTimer;

    const updateReviews = () => {
        const card = reviewCards[0];
        const gap = Number.parseFloat(getComputedStyle(reviewsTrack).gap) || 0;
        const step = card.getBoundingClientRect().width + gap;
        reviewsTrack.scrollTo({ left: reviewIndex * step, behavior: "smooth" });
        const status = reviewsCarousel.querySelector("[data-reviews-status]");
        if (status) {
            status.textContent = `${String(reviewIndex + 1).padStart(2, "0")} / ${String(reviewCards.length).padStart(2, "0")}`;
        }
    };

    const moveReviews = (direction) => {
        reviewIndex = (reviewIndex + direction + reviewCards.length) % reviewCards.length;
        updateReviews();
    };

    const startReviewTimer = () => {
        window.clearInterval(reviewTimer);
        reviewTimer = window.setInterval(() => moveReviews(1), 5000);
    };

    reviewsCarousel.querySelector("[data-reviews-prev]")?.addEventListener("click", () => {
        moveReviews(-1);
        startReviewTimer();
    });
    reviewsCarousel.querySelector("[data-reviews-next]")?.addEventListener("click", () => {
        moveReviews(1);
        startReviewTimer();
    });
    reviewsTrack.addEventListener("scroll", () => {
        const card = reviewCards[0];
        const gap = Number.parseFloat(getComputedStyle(reviewsTrack).gap) || 0;
        const step = card.getBoundingClientRect().width + gap;
        reviewIndex = Math.round(reviewsTrack.scrollLeft / step) % reviewCards.length;
        const status = reviewsCarousel.querySelector("[data-reviews-status]");
        if (status) {
            status.textContent = `${String(reviewIndex + 1).padStart(2, "0")} / ${String(reviewCards.length).padStart(2, "0")}`;
        }
        startReviewTimer();
    });
    reviewsTrack.addEventListener("pointerenter", () => window.clearInterval(reviewTimer));
    reviewsTrack.addEventListener("pointerleave", startReviewTimer);
    window.addEventListener("resize", updateReviews);
    updateReviews();
    startReviewTimer();
}

const mobileMenuToggle = document.querySelector("[data-mobile-menu-toggle]");
const mobileMenuLinks = document.querySelector("[data-mobile-menu-links]");

if (mobileMenuToggle && mobileMenuLinks) {
    const closeMobileMenu = () => {
        mobileMenuLinks.classList.remove("is-open");
        mobileMenuToggle.setAttribute("aria-expanded", "false");
    };

    mobileMenuToggle.addEventListener("click", () => {
        const isOpen = mobileMenuLinks.classList.toggle("is-open");
        mobileMenuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    mobileMenuLinks.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", closeMobileMenu);
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeMobileMenu();
        }
    });
}

const navigationLinks = document.querySelectorAll(".nav-link");
const navigationSections = document.querySelectorAll(
    "#tentang-kami, #layanan, #cara-kerja, #kontak",
);

if (navigationLinks.length && navigationSections.length) {
    const sectionObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                navigationLinks.forEach((link) => {
                    link.classList.toggle(
                        "is-active",
                        link.dataset.section === entry.target.id,
                    );
                });
            });
        },
        { rootMargin: "-35% 0px -55% 0px", threshold: 0 },
    );

    navigationSections.forEach((section) => sectionObserver.observe(section));
}

const revealItems = document.querySelectorAll(
    "main section, .service-card, .review-card, .process-list li",
);

if ("IntersectionObserver" in window && revealItems.length) {
    const revealObserver = new IntersectionObserver(
        (entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add("is-visible");
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.12, rootMargin: "0px 0px -7% 0px" },
    );

    revealItems.forEach((item, index) => {
        item.classList.add("scroll-reveal");
        item.style.setProperty(
            "--reveal-delay",
            `${Math.min(index % 5, 4) * 70}ms`,
        );
        revealObserver.observe(item);
    });
} else {
    revealItems.forEach((item) => item.classList.add("is-visible"));
}
