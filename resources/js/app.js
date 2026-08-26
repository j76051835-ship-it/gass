import "./bootstrap";
import React from "react";
import { createRoot } from "react-dom/client";
import { DotLottieReact } from "@lottiefiles/dotlottie-react";

const adminRobot = document.querySelector("[data-admin-robot]");

if (adminRobot) {
    const robotEyes = [...adminRobot.querySelectorAll(".robot-eye")];
    window.addEventListener("pointermove", (event) => {
        robotEyes.forEach((eye) => {
            const bounds = eye.getBoundingClientRect();
            const horizontal = (event.clientX - (bounds.left + bounds.width / 2)) / (bounds.width / 2);
            const vertical = (event.clientY - (bounds.top + bounds.height / 2)) / (bounds.height / 2);
            eye.style.setProperty("--pupil-x", `${Math.max(-12, Math.min(12, horizontal * 12))}px`);
            eye.style.setProperty("--pupil-y", `${Math.max(-12, Math.min(12, vertical * 12))}px`);
        });
    }, { passive: true });
}

const lottieMount = document.querySelector(".about-visual");

if (lottieMount) {
    lottieMount.classList.add("lottie-visual");
    const animationMount = document.createElement("div");
    animationMount.className = "lottie-animation-mount";
    animationMount.setAttribute("aria-label", "Animasi profesional bekerja di depan komputer");
    lottieMount.prepend(animationMount);
    createRoot(animationMount).render(
        React.createElement(DotLottieReact, {
            src: "https://lottie.host/65303e49-f161-4757-962d-941db72373d9/6z0fPvOi90.json",
            loop: true,
            autoplay: true,
        }),
    );
}

let pointerFrame = null;
window.addEventListener("pointermove", (event) => {
    if (pointerFrame) return;
    pointerFrame = window.requestAnimationFrame(() => {
        document.body.style.setProperty("--pointer-x", `${event.clientX}px`);
        document.body.style.setProperty("--pointer-y", `${event.clientY}px`);
        document.body.style.setProperty("--sketchfab-shift-x", `${(window.innerWidth / 2 - event.clientX) * 0.018}px`);
        document.body.style.setProperty("--sketchfab-shift-y", `${(window.innerHeight / 2 - event.clientY) * 0.018}px`);
        pointerFrame = null;
    });
}, { passive: true });

const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
const siteHeader = document.querySelector(".site-header");
const pulseCart = () => {
    if (reducedMotion.matches) return;
    const cartButton = document.querySelector(".request-cart-trigger");
    if (!cartButton) return;
    cartButton.classList.remove("is-pulsing");
    window.requestAnimationFrame(() => cartButton.classList.add("is-pulsing"));
};

const startContentTyping = () => {
    if (reducedMotion.matches) return;
    const contentElements = document.querySelectorAll("main h1, main h2, main h3, main p, main li");
    contentElements.forEach((element, elementIndex) => {
        if (element.closest(".package-panel, .order-modal, .request-checkout")) return;
        const textNodes = [];
        const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, {
            acceptNode: (node) => node.textContent.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT,
        });
        let node;
        while ((node = walker.nextNode())) textNodes.push({ node, text: node.textContent });
        if (!textNodes.length) return;
        element.setAttribute("aria-label", element.textContent.trim());
        textNodes.forEach(({ node, text }, nodeIndex) => {
            node.textContent = "";
            let characterIndex = 0;
            window.setTimeout(() => {
                const timer = window.setInterval(() => {
                    node.textContent = text.slice(0, characterIndex + 1);
                    characterIndex += 1;
                    if (characterIndex >= text.length) window.clearInterval(timer);
                }, 5);
            }, elementIndex * 6 + nodeIndex * 15);
        });
    });
};

window.setTimeout(startContentTyping, 120);

window.addEventListener("scroll", () => {
    siteHeader?.classList.toggle("is-scrolled", window.scrollY > 24);
}, { passive: true });

if (!reducedMotion.matches) {
    document.querySelectorAll(".service-card, .package-option, .integrated-service").forEach((card) => {
        card.addEventListener("pointermove", (event) => {
            const bounds = card.getBoundingClientRect();
            const rotateX = ((event.clientY - bounds.top) / bounds.height - 0.5) * -3;
            const rotateY = ((event.clientX - bounds.left) / bounds.width - 0.5) * 4;
            card.style.setProperty("--tilt-x", `${rotateX}deg`);
            card.style.setProperty("--tilt-y", `${rotateY}deg`);
        });
        card.addEventListener("pointerleave", () => {
            card.style.setProperty("--tilt-x", "0deg");
            card.style.setProperty("--tilt-y", "0deg");
        });
    });
}

document.addEventListener("pointerdown", (event) => {
    const button = event.target.closest("button, .button");
    if (!button || reducedMotion.matches) return;
    const ripple = document.createElement("i");
    const bounds = button.getBoundingClientRect();
    ripple.className = "interaction-ripple";
    ripple.style.left = `${event.clientX - bounds.left}px`;
    ripple.style.top = `${event.clientY - bounds.top}px`;
    button.append(ripple);
    ripple.addEventListener("animationend", () => ripple.remove(), { once: true });
}, { passive: true });

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

document.querySelectorAll("[data-review-media]").forEach((input) => {
    input.addEventListener("change", () => {
        const files = [...input.files];
        const maxFileSize = 20 * 1024 * 1024;
        const hasInvalidFile = files.length > 3 || files.some((file) => file.size > maxFileSize);
        if (!hasInvalidFile) return;
        window.alert(files.length > 3 ? "Maksimal 3 file yang dapat diupload." : "Ukuran setiap file maksimal 20 MB.");
        input.value = "";
    });
});

const aboutVisual = document.querySelector(".about-visual");

if (aboutVisual) {
    const dashboardObserver = new IntersectionObserver(([entry]) => {
        if (!entry.isIntersecting) return;
        aboutVisual.classList.add("dashboard-live");
        window.setTimeout(() => aboutVisual.classList.add("dashboard-data-live"), 750);
        window.setTimeout(() => aboutVisual.classList.add("campaign-success"), 2200);
        dashboardObserver.disconnect();
    }, { threshold: .35 });
    dashboardObserver.observe(aboutVisual);
}

const aboutBook = document.querySelector("[data-about-book]");
const aboutBookPages = aboutBook?.querySelector("[data-about-book-pages]");
const aboutBookCards = aboutBookPages ? [...aboutBookPages.querySelectorAll(".about-service-card")] : [];

if (aboutBook && aboutBookPages && aboutBookCards.length > 1) {
    let currentBookPage = 0;
    let touchStartX = 0;
    let wheelLocked = false;
    const pageHint = aboutBook.querySelector(".about-service-hint span:last-child");

    const showBookPage = (nextPage) => {
        const targetPage = Math.max(0, Math.min(nextPage, aboutBookCards.length - 1));
        if (targetPage === currentBookPage) return;
        const direction = targetPage > currentBookPage ? "next" : "prev";
        const oldCard = aboutBookCards[currentBookPage];
        const newCard = aboutBookCards[targetPage];
        aboutBookPages.classList.remove("is-flipping-next", "is-flipping-prev");
        aboutBookPages.classList.add(`is-flipping-${direction}`);
        oldCard.classList.remove("is-active");
        oldCard.classList.add("is-turning");
        newCard.classList.remove("is-turning");
        newCard.classList.add("is-active");
        currentBookPage = targetPage;
        if (pageHint) pageHint.textContent = `${String(currentBookPage + 1).padStart(2, "0")} / ${String(aboutBookCards.length).padStart(2, "0")}`;
        window.setTimeout(() => {
            oldCard.classList.remove("is-turning");
            aboutBookPages.classList.remove("is-flipping-next", "is-flipping-prev");
        }, 850);
    };

    aboutBookPages.classList.add("is-ready");
    aboutBookCards[0].classList.add("is-active");
    aboutBook.querySelector("[data-about-book-prev]")?.addEventListener("click", () => showBookPage(currentBookPage - 1));
    aboutBook.querySelector("[data-about-book-next]")?.addEventListener("click", () => showBookPage(currentBookPage + 1));
    const bookObserver = new IntersectionObserver(([entry]) => {
        if (!entry.isIntersecting) return;
        aboutBook.classList.add("is-opening");
        bookObserver.disconnect();
    }, { threshold: .35 });
    bookObserver.observe(aboutBook);

    aboutBook.addEventListener("wheel", (event) => {
        if (wheelLocked || Math.abs(event.deltaY) < 12) return;
        const nextPage = currentBookPage + (event.deltaY > 0 ? 1 : -1);
        if (nextPage === currentBookPage) return;
        event.preventDefault();
        wheelLocked = true;
        showBookPage(nextPage);
        window.setTimeout(() => { wheelLocked = false; }, 900);
    }, { passive: false });

    aboutBook.addEventListener("touchstart", (event) => {
        touchStartX = event.changedTouches[0].clientX;
    }, { passive: true });
    aboutBook.addEventListener("touchend", (event) => {
        const distance = event.changedTouches[0].clientX - touchStartX;
        if (Math.abs(distance) < 45) return;
        showBookPage(currentBookPage + (distance < 0 ? 1 : -1));
    }, { passive: true });

    aboutBookPages.addEventListener("pointerdown", (event) => {
        if (event.pointerType === "mouse") return;
        aboutBookPages.setPointerCapture(event.pointerId);
        touchStartX = event.clientX;
    });
    aboutBookPages.addEventListener("pointerup", (event) => {
        if (event.pointerType === "mouse") return;
        const distance = event.clientX - touchStartX;
        if (Math.abs(distance) >= 45) showBookPage(currentBookPage + (distance < 0 ? 1 : -1));
    });

    if (window.matchMedia("(hover: hover)").matches && !reducedMotion.matches) {
        aboutBook.addEventListener("pointermove", (event) => {
            const bounds = aboutBook.getBoundingClientRect();
            const rotateX = ((event.clientY - bounds.top) / bounds.height - .5) * -2.5;
            const rotateY = ((event.clientX - bounds.left) / bounds.width - .5) * 4;
            aboutBookPages.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });
        aboutBook.addEventListener("pointerleave", () => {
            aboutBookPages.style.transform = "rotateX(0deg) rotateY(0deg)";
        });
    }
}

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

const orderModal = document.querySelector("[data-order-modal]");
const orderForm = orderModal?.querySelector("[data-order-form]");

if (orderModal && orderForm) {
    let orderSteps = orderForm.querySelectorAll("[data-order-step]");
    let progressSteps = orderModal.querySelectorAll("[data-order-progress]");
    const orderTitle = orderModal.querySelector("[data-order-title]");
    const selectedPackage = orderModal.querySelector("[data-order-selected]");
    const selectedPrice = orderModal.querySelector("[data-order-selected-price]");
    const liveTotal = document.createElement("span");
    liveTotal.dataset.orderLiveTotal = "";
    orderModal.querySelector(".order-summary")?.append(liveTotal);
    const invoicePackage = orderModal.querySelector("[data-invoice-package]");
    const invoiceQuantity = orderModal.querySelector("[data-invoice-quantity]");
    const invoiceAddon = orderModal.querySelector("[data-invoice-addon]");
    const invoiceTotal = orderModal.querySelector("[data-invoice-total]");
    const invoiceBox = invoiceTotal.closest(".invoice-box");
    const cartPackage = document.createElement("span");
    const cartQuantity = document.createElement("span");
    const cartTotal = document.createElement("span");
    const cartDiscount = document.createElement("span");
    const cartStep = document.createElement("section");
    const invoiceStep = orderForm.querySelector('[data-order-step="2"]');
    cartStep.className = "order-step";
    cartStep.dataset.orderStep = "2";
    cartStep.innerHTML = '<p class="order-lead">Pesananmu sudah disesuaikan. Periksa kembali sebelum membuat invoice.</p><div class="cart-card"><span class="eyebrow">Keranjang Pesanan</span><strong data-cart-package></strong><div><span data-cart-quantity></span><span data-cart-total></span></div></div><div class="cart-items"></div><button class="cart-add" type="button" data-cart-add>+ Tambah Paket Lain</button><button class="order-next" type="button" data-order-next>Lanjut ke Invoice <span>→</span></button><button class="order-back" type="button" data-order-back>Kembali</button>';
        cartStep.innerHTML = '<p class="order-lead">Pesananmu sudah disesuaikan. Periksa kembali sebelum membuat invoice.</p><div class="cart-card"><span class="eyebrow">Keranjang Pesanan</span><strong data-cart-package></strong><div><span data-cart-quantity></span><span data-cart-subtotal></span></div><div><span>Diskon</span><span data-cart-discount></span></div><div><strong>Total setelah diskon</strong><strong data-cart-total></strong></div></div><div class="cart-items"></div><button class="cart-add" type="button" data-cart-add>+ Tambah Paket Lain</button><button class="order-next" type="button" data-order-next>Lanjut ke Invoice <span>→</span></button><button class="order-back" type="button" data-order-back>Kembali</button>';
    invoiceStep.dataset.orderStep = "3";
    invoiceStep.nextElementSibling.dataset.orderStep = "4";
    invoiceStep.nextElementSibling.nextElementSibling.dataset.orderStep = "5";
    invoiceStep.before(cartStep);
    orderSteps = orderForm.querySelectorAll("[data-order-step]");
    const dynamicProgress = orderModal.querySelector(".order-progress");
    const progressDivider = document.createElement("i");
    const progressFive = document.createElement("span");
    progressFive.dataset.orderProgress = "5";
    progressFive.textContent = "05";
    dynamicProgress.append(progressDivider, progressFive);
    progressSteps = orderModal.querySelectorAll("[data-order-progress]");
    cartPackage.textContent = "";
    cartQuantity.textContent = "";
    cartTotal.textContent = "";
    const cartCard = cartStep.querySelector(".cart-card");
    cartCard.querySelector("[data-cart-package]").replaceWith(cartPackage);
    cartCard.querySelector("[data-cart-quantity]").replaceWith(cartQuantity);
    cartCard.querySelector("[data-cart-total]").replaceWith(cartTotal);
        cartCard.querySelector("[data-cart-discount]").replaceWith(cartDiscount);
    const successPanel = orderModal.querySelector("[data-order-success]");
    const quantityInput = orderForm.elements.quantity;
    let currentStep = 1;
    let chosenPackage = "AI Video Standard";
    let chosenPrice = 750000;
    let cartItems = [];
    let editingCartIndex = null;
    const packageFeatures = {
        "AI Video Basic": ["Durasi 15 - 30 detik", "1 konsep konten", "1 - 3 scene", "Visual AI basic", "Basic animation", "Background music", "Subtitle", "Basic editing", "Format 9:16", "Resolusi Full HD", "Revisi 1x"],
        "AI Video Standard": ["Durasi 30 - 60 detik", "Konsep & storyline", "Script/copywriting sederhana", "3 - 6 scene", "Visual AI lebih detail", "AI character sederhana", "AI voice over", "Motion/animation", "Professional editing", "Logo & branding klien", "Format 9:16 / 16:9", "Resolusi Full HD", "Revisi 2x"],
        "AI Video Premium": ["Durasi 60 - 90 detik", "Creative concept", "Storytelling", "Script profesional", "Storyboard sederhana", "6 - 10 scene", "Premium AI visual", "Custom AI character", "Character consistency", "Advanced animation", "Cinematic transition", "CTA / Call to Action", "Revisi 2x"],
        "AI Video Pro": ["Durasi 60 - 120 detik", "Creative direction", "Konsep campaign", "Professional storytelling", "Professional script", "Storyboard", "8 - 15+ scene", "Cinematic AI visual", "Custom AI character", "Consistent environment", "Product placement", "Advanced AI animation", "Motion graphic", "CTA & campaign message", "Multiple format output", "Revisi 3x"],
        "AI Video Basic Bulanan": ["4 video per bulan", "Durasi 15 - 30 detik", "Konten rutin"],
        "AI Video Standard Bulanan": ["8 video per bulan", "Durasi 30 - 60 detik", "Konten bisnis"],
        "AI Video Premium Bulanan": ["12 video per bulan", "Durasi 30 - 90 detik", "Konten marketing"],
        "AI Video Pro Bulanan": ["20 video per bulan", "Durasi 30 - 120 detik", "Content Planning", "Creative Direction", "Branding", "Social Media Support"],
        "Foto AI Basic": ["1 - 3 Slide", "Desain Profesional", "Foto / Ilustrasi AI", "Revisi 1x"],
        "Foto AI Standard": ["4 - 6 Slide", "Desain Profesional", "Foto / Ilustrasi AI", "Revisi 2x"],
        "Foto AI Premium": ["7 - 10 Slide", "Desain Premium", "Foto / Ilustrasi AI", "Revisi 3x"],
    };

    const formatPrice = (price) => `Rp ${new Intl.NumberFormat("id-ID").format(price)}`;
    const getQuantity = () => Math.max(1, Number(quantityInput.value) || 1);
    const getCartTotal = () => cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
    const getCartSubtotal = () => cartItems.reduce((total, item) => total + item.basePrice * item.quantity, 0);
    const renderChecklist = (packageName, duration = "") => `${(packageFeatures[packageName] || []).map((feature) => `<label class="order-check"><input name="packageFeature" type="checkbox" value="${feature}" checked> ${feature}</label>`).join("")}${packageName === "AI Video Pro" ? `<label>Durasi Video<select name="video_duration" required><option ${duration === "15 - 30 detik" ? "selected" : ""}>15 - 30 detik</option><option ${duration === "30 - 60 detik" ? "selected" : ""}>30 - 60 detik</option><option ${duration === "60 - 90 detik" ? "selected" : ""}>60 - 90 detik</option><option ${duration === "60 - 120 detik" || !duration ? "selected" : ""}>60 - 120 detik</option></select></label>` : ""}<legend class="order-extra-title">Tambahan kebutuhan</legend>`;

    const showStep = (step) => {
        currentStep = step;
        orderSteps.forEach((item) => item.classList.toggle("is-active", Number(item.dataset.orderStep) === step));
        progressSteps.forEach((item) => item.classList.toggle("is-active", Number(item.dataset.orderProgress) <= step));
        orderTitle.textContent = ["Ceklis Pesanan", "Keranjang Pesanan", "Invoice Pemesanan", "Pembayaran", "Konfirmasi"][step - 1];
    };

    const renderCart = () => {
        const items = cartStep.querySelector(".cart-items");
        items.innerHTML = cartItems.map((item, index) => `<div class="cart-item"><strong>${item.package}</strong><span class="cart-item-price">${formatPrice(item.price)} / item${item.discount ? ` · Diskon ${item.discount}%` : ""}</span><div class="cart-quantity"><button type="button" data-cart-decrease="${index}" aria-label="Kurangi jumlah ${item.package}">-</button><strong>${item.quantity}</strong><button type="button" data-cart-increase="${index}" aria-label="Tambah jumlah ${item.package}">+</button><span>${formatPrice(item.price * item.quantity)}</span></div><div class="cart-item-actions"><button type="button" data-cart-edit="${index}">Kustomisasi</button><button type="button" data-cart-remove="${index}">Hapus</button></div></div>`).join("");
        const cartSubtotal = cartItems.reduce((total, item) => total + item.basePrice * item.quantity, 0);
        const cartFinalTotal = getCartTotal();
        cartStep.querySelector("[data-cart-subtotal]").textContent = formatPrice(cartSubtotal);
        cartStep.querySelector("[data-cart-discount]").textContent = formatPrice(cartSubtotal - cartFinalTotal);
        cartStep.querySelector("[data-cart-total]").textContent = formatPrice(cartFinalTotal);
        cartStep.querySelector("[data-cart-quantity]").textContent = `${cartItems.length} paket berbeda`;
        cartItemsContainer.querySelectorAll("[data-cart-decrease], [data-cart-increase]").forEach((button) => {
            button.addEventListener("click", () => {
                const item = cartItems[Number(button.dataset.cartDecrease ?? button.dataset.cartIncrease)];
                if (!item) return;
                item.quantity = Math.max(1, item.quantity + (button.dataset.cartIncrease !== undefined ? 1 : -1));
                renderCart();
                updateInvoice();
            });
        });
        cartItemsContainer.querySelectorAll("[data-cart-remove]").forEach((button) => {
            button.addEventListener("click", () => {
                cartItems.splice(Number(button.dataset.cartRemove), 1);
                renderCart();
            });
        });
        cartItemsContainer.querySelectorAll("[data-cart-edit]").forEach((button) => {
            button.addEventListener("click", () => editCartItem(Number(button.dataset.cartEdit)));
        });
    };

    const editCartItem = (index) => {
        const item = cartItems[index];
        if (!item) {
            return;
        }

        editingCartIndex = index;
        chosenPackage = item.package;
        chosenPrice = item.price;
        quantityInput.value = item.quantity;
        selectedPackage.textContent = chosenPackage;
        selectedPrice.textContent = `${formatPrice(chosenPrice)} / item`;
        const checklist = orderForm.querySelector("fieldset");
        checklist.innerHTML = `<legend>Checklist Paket</legend>${renderChecklist(chosenPackage, item.duration)}<label class="order-check"><input name="addon" value="Konsep dan storyboard" type="checkbox" ${item.addons.includes("Konsep dan storyboard") ? "checked" : ""}> Konsep dan storyboard</label><label class="order-check"><input name="addon" value="Prioritas pengerjaan" type="checkbox" ${item.addons.includes("Prioritas pengerjaan") ? "checked" : ""}> Prioritas pengerjaan</label>`;
        orderForm.elements.brief.value = item.brief || "";
        showStep(1);
    };

    const addToCart = () => {
        const quantity = getQuantity();
        const addons = [...orderForm.querySelectorAll("input[name=addon]:checked")].map((input) => input.value);
        const duration = orderForm.elements.video_duration?.value || "";
        if (editingCartIndex !== null && cartItems[editingCartIndex]) {
            cartItems[editingCartIndex] = { ...cartItems[editingCartIndex], quantity, duration, addons, brief: orderForm.elements.brief.value };
            editingCartIndex = null;
        } else if (cartItems.some((item) => item.package === chosenPackage && item.duration === duration)) {
            const existingItem = cartItems.find((item) => item.package === chosenPackage && item.duration === duration);
            existingItem.quantity += quantity;
            existingItem.addons = [...new Set([...existingItem.addons, ...addons])];
        } else {
            cartItems.push({ package: chosenPackage, price: chosenPrice, basePrice: Number(orderForm.dataset.orderBasePrice || chosenPrice), discount: Number(orderForm.dataset.orderDiscount || 0), quantity, duration, addons, brief: orderForm.elements.brief.value });
        }
        renderCart();
        pulseCart();
    };

    const updateInvoice = () => {
        const quantity = getQuantity();
        const addons = [...orderForm.querySelectorAll("input[name=addon]:checked")].map((input) => input.value);
        liveTotal.textContent = `Total: ${formatPrice(quantity * chosenPrice)}`;
        invoicePackage.textContent = cartItems.map((item) => item.duration ? `${item.package} (${item.duration})` : item.package).join(", ");
        invoiceQuantity.textContent = cartItems.map((item) => `${item.quantity} x ${formatPrice(item.price)}`).join(" + ");
        invoiceAddon.textContent = [...new Set(cartItems.flatMap((item) => item.addons))].concat(addons).join(", ") || "Tidak ada";
        let invoiceSubtotal = invoiceBox.querySelector("[data-invoice-subtotal]");
        let invoiceDiscount = invoiceBox.querySelector("[data-invoice-discount]");
        if (!invoiceSubtotal) {
            const subtotalRow = document.createElement("div");
            subtotalRow.innerHTML = "<span>Subtotal</span><strong data-invoice-subtotal></strong>";
            const discountRow = document.createElement("div");
            discountRow.innerHTML = "<span>Diskon</span><strong data-invoice-discount></strong>";
            invoiceTotal.closest("div").before(subtotalRow, discountRow);
            invoiceSubtotal = subtotalRow.querySelector("[data-invoice-subtotal]");
            invoiceDiscount = discountRow.querySelector("[data-invoice-discount]");
        }
        invoiceSubtotal.textContent = formatPrice(getCartSubtotal());
        invoiceDiscount.textContent = formatPrice(getCartSubtotal() - getCartTotal());
        invoiceTotal.textContent = formatPrice(getCartTotal());
        cartPackage.textContent = cartItems.map((item) => item.package).join(", ");
        cartQuantity.textContent = `${cartItems.length} paket berbeda`;
        cartTotal.textContent = formatPrice(getCartTotal());
    };

    const openOrder = (button) => {
        chosenPackage = button.dataset.orderPackage;
        chosenPrice = Number(button.dataset.orderPrice);
        const packageData = servicePackages[chosenPackage];
        if (packageData) {
            chosenPrice = Number(packageData.price);
            orderForm.dataset.orderBasePrice = packageData.basePrice;
            orderForm.dataset.orderDiscount = packageData.discount;
        }
        orderForm.dataset.orderBasePrice = button.dataset.orderBasePrice || chosenPrice;
        orderForm.dataset.orderDiscount = button.dataset.orderDiscount || 0;
        selectedPackage.textContent = chosenPackage;
        selectedPrice.textContent = `${formatPrice(chosenPrice)} / item`;
        const checklist = orderForm.querySelector("fieldset");
        checklist.innerHTML = `<legend>Checklist Paket</legend>${renderChecklist(chosenPackage)}<label class="order-check"><input name="addon" value="Konsep dan storyboard" type="checkbox"> Konsep dan storyboard</label><label class="order-check"><input name="addon" value="Prioritas pengerjaan" type="checkbox"> Prioritas pengerjaan</label>`;
        updateInvoice();
        orderModal.classList.add("is-open");
        orderModal.setAttribute("aria-hidden", "false");
        document.body.classList.add("order-modal-open");
        document.body.style.overflow = "hidden";
        showStep(1);
    };

    const closeOrder = () => {
        orderModal.classList.remove("is-open");
        orderModal.setAttribute("aria-hidden", "true");
        document.body.classList.remove("order-modal-open");
        document.body.style.overflow = "";
    };

    document.querySelectorAll("[data-order-package]").forEach((button) => {
        button.textContent = "Tambah ke Keranjang";
        button.addEventListener("click", () => {
            editingCartIndex = null;
            openOrder(button);
        });
    });
    document.querySelectorAll(".package-option").forEach((card) => {
        card.addEventListener("click", (event) => {
            if (event.target.closest("button, a")) {
                return;
            }

            card.querySelector("[data-order-package]")?.click();
        });
    });
    cartStep.querySelector("[data-cart-add]").addEventListener("click", () => {
        closeOrder();
        document.querySelector(".package-grid")?.scrollIntoView({ behavior: "smooth", block: "start" });
    });
    quantityInput.addEventListener("input", updateInvoice);
    orderModal.querySelector("[data-order-close]").addEventListener("click", closeOrder);
    orderModal.addEventListener("click", (event) => {
        if (event.target === orderModal) {
            closeOrder();
        }
    });
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && orderModal.classList.contains("is-open")) {
            closeOrder();
        }
    });

    orderForm.querySelectorAll("[data-order-next]").forEach((button) => {
        button.addEventListener("click", () => {
            const step = button.closest("[data-order-step]");
            const fields = [...(step?.querySelectorAll("input, textarea, select") || [])];
            const isValid = fields.every((field) => field.checkValidity());
            if (!isValid) {
                fields.find((field) => !field.checkValidity())?.reportValidity();
                return;
            }

            if (step) {
                if (Number(step.dataset.orderStep) === 1) {
                    addToCart();
                }
                updateInvoice();
                showStep(Math.min(currentStep + 1, 5));
            }
        });
    });
    orderForm.querySelectorAll("[data-order-back]").forEach((button) => {
        button.addEventListener("click", () => showStep(Math.max(currentStep - 1, 1)));
    });
    orderForm.addEventListener("submit", (event) => {
        event.preventDefault();
        if (!orderForm.reportValidity()) {
            return;
        }

        const quantity = Number(quantityInput.value) || 1;
        const addons = [...orderForm.querySelectorAll("input[name=addon]:checked")].map((input) => input.value);
        const customerName = orderForm.elements.name.value;
        const contact = orderForm.elements.contact.value;
        const orderLines = cartItems.map((item) => `${item.package}${item.duration ? ` (${item.duration})` : ""} | Jumlah: ${item.quantity} | Harga: ${formatPrice(item.price * item.quantity)}`);
        const message = `Halo GASS, saya ${customerName} ingin konfirmasi pesanan.\n\n${orderLines.join("\n")}\nTambahan: ${addons.length ? addons.join(", ") : "Tidak ada"}\nTotal: ${formatPrice(getCartTotal())}\nKontak: ${contact}`;
        orderModal.querySelector("[data-order-whatsapp]").href = `https://wa.me/6285890007359?text=${encodeURIComponent(message)}`;
        orderSteps.forEach((step) => step.classList.remove("is-active"));
        successPanel.hidden = false;
        orderTitle.textContent = "Konfirmasi Terkirim";
        progressSteps.forEach((step) => step.classList.add("is-active"));
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
    "main section, .service-card, .review-card, .process-list li, .integrated-service",
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

const servicePackageData = document.querySelector("#service-package-data");
const servicePackages = servicePackageData ? JSON.parse(servicePackageData.textContent) : {};
const requestPackageButtons = document.querySelectorAll("[data-order-package]");

if (requestPackageButtons.length) {
    const requestPackages = {};
    requestPackageButtons.forEach((button) => {
        const packageData = servicePackages[button.dataset.orderPackage];
        requestPackages[button.dataset.orderPackage] = packageData || {
            price: Number(button.dataset.orderPrice),
            basePrice: Number(button.dataset.orderBasePrice || button.dataset.orderPrice),
            discount: Number(button.dataset.orderDiscount || 0),
        };
    });

    const requestCheckout = document.createElement("div");
    requestCheckout.className = "request-checkout";
    requestCheckout.hidden = true;
    requestCheckout.innerHTML = `<div class="request-dialog" role="dialog" aria-modal="true" aria-labelledby="request-title"><div class="request-head"><div><p class="eyebrow">Pemesanan by request</p><h2 id="request-title">Keranjang Pesanan</h2></div><button class="request-close" type="button" aria-label="Tutup pemesanan">×</button></div><div class="request-steps"><span class="is-active" data-request-step="1">01 Keranjang</span><span data-request-step="2">02 Form</span><span data-request-step="3">03 Review</span><span data-request-step="4">04 Invoice</span><span data-request-step="5">05 Bayar</span></div><section class="request-panel" data-request-panel="1"><h3>Produk pilihanmu</h3><p>Tambahkan beberapa paket berbeda dan sesuaikan pesanan sebelum lanjut.</p><div class="request-cart" data-request-cart></div><div class="request-total"><span>Total sementara</span><strong data-request-total>Rp 0</strong></div><div class="request-actions"><button type="button" class="request-secondary" data-request-continue>Tambah Paket Lain</button><button type="button" data-request-next="2">Lanjutkan Pemesanan <span>→</span></button></div></section><section class="request-panel" data-request-panel="2" hidden><h3>Form Pemesanan</h3><p>Isi data pemesan dan detail kebutuhan by request.</p><form data-request-details><div class="request-grid"><label class="request-field">Nama Lengkap<input name="customer_name" required></label><label class="request-field">Nama Usaha / Brand<input name="brand_name" required></label><label class="request-field">Nomor WhatsApp<input name="whatsapp" type="tel" required></label><label class="request-field">Email<input name="email" type="email" required></label><label class="request-field full">Alamat <small>(Opsional)</small><textarea name="address" rows="2"></textarea></label></div><div data-request-product-fields></div><div class="request-actions"><button type="button" class="request-secondary" data-request-back="1">Kembali</button><button type="submit" data-request-next="3">Lanjutkan Review <span>→</span></button></div></form></section><section class="request-panel" data-request-panel="3" hidden><h3>Review Pesanan</h3><p>Pastikan data dan detail kebutuhan sudah sesuai.</p><div class="request-review" data-request-review></div><div class="request-actions"><button type="button" class="request-secondary" data-request-back="2">Kembali</button><button type="button" data-request-next="4">Buat Invoice <span>→</span></button></div></section><section class="request-panel" data-request-panel="4" hidden><h3>Invoice Pemesanan</h3><p>Invoice otomatis dibuat dengan status pembayaran pending.</p><div class="request-invoice" data-request-invoice></div><div class="request-actions"><button type="button" class="request-secondary" data-request-back="3">Kembali</button><button type="button" data-request-next="5">Bayar Sekarang <span>→</span></button></div></section><section class="request-panel" data-request-panel="5" hidden><h3>Pembayaran</h3><p>Selesaikan pembayaran, lalu kirim konfirmasi ke tim GASS.</p><div class="request-payment"><strong>Bank BCA</strong><b>1234 5678 90</b><span>a.n. PT GASS Digital Solutions</span></div><label class="request-field">Metode Pembayaran<select name="request_payment"><option>Transfer BCA</option><option>QRIS</option></select></label><div class="request-actions"><button type="button" class="request-secondary" data-request-back="4">Kembali</button><button type="button" data-request-paid>Tandai Sudah Bayar <span>✓</span></button></div><div class="request-invoice" data-request-paid-state hidden></div></section></div>`;
    document.body.append(requestCheckout);

    requestCheckout.querySelector('[data-request-step="5"]')?.remove();
    requestCheckout.querySelector('[data-request-panel="5"]')?.remove();

    let requestCart = [];
    let requestStep = 1;
    let requestInvoice = "";
    const requestDialog = requestCheckout.querySelector(".request-dialog");
    const requestTitle = requestCheckout.querySelector("#request-title");
    const requestCartView = requestCheckout.querySelector("[data-request-cart]");
    const requestTotalView = requestCheckout.querySelector("[data-request-total]");
    const requestDetails = requestCheckout.querySelector("[data-request-details]");
    const requestProductFields = requestCheckout.querySelector("[data-request-product-fields]");
    const requestReview = requestCheckout.querySelector("[data-request-review]");
    const requestInvoiceView = requestCheckout.querySelector("[data-request-invoice]");
    const requestPaidState = requestCheckout.querySelector("[data-request-paid-state]");
    const requestIdentityFields = ["whatsapp", "email", "address"];
    requestIdentityFields.concat("brand_name").forEach((name) => requestDetails.elements[name]?.closest("label")?.remove());
    const requestNameLabel = requestDetails.elements.customer_name?.closest("label");
    if (requestNameLabel) requestNameLabel.firstChild.textContent = "Nama / Perusahaan";
    const requestCartTrigger = document.createElement("button");
    requestCartTrigger.className = "request-cart-trigger";
    requestCartTrigger.type = "button";
    requestCartTrigger.innerHTML = "Keranjang <span>0</span>";
    const requestDrawerBackdrop = document.createElement("div");
    requestDrawerBackdrop.className = "request-drawer-backdrop";
    requestDrawerBackdrop.hidden = true;
    const requestDrawer = document.createElement("aside");
    requestDrawer.className = "request-drawer";
    requestDrawer.setAttribute("aria-label", "Keranjang pesanan");
    requestDrawer.innerHTML = '<div class="request-drawer-head"><h2>Keranjang</h2><button class="request-drawer-close" type="button" aria-label="Tutup keranjang">×</button></div><p class="request-lead">Atur dan kustomisasi setiap paket langsung di keranjang.</p><label class="request-field">Nama / Perusahaan<input data-drawer-customer placeholder="Contoh: Toko Maju Jaya atau Budi Santoso" required></label><div class="request-cart" data-drawer-cart></div><div class="request-total"><span>Total</span><strong data-drawer-total>Rp 0</strong></div><button class="order-next" type="button" data-drawer-continue>Lanjutkan ke Invoice <span>→</span></button>';
    document.body.append(requestCartTrigger, requestDrawerBackdrop, requestDrawer);
    const drawerCustomer = requestDrawer.querySelector("[data-drawer-customer]");
    const requestFormatPrice = (price) => `Rp ${new Intl.NumberFormat("id-ID").format(price)}`;
    const requestTotal = () => requestCart.reduce((total, item) => total + item.price * item.quantity, 0);
    const requestSubtotal = () => requestCart.reduce((total, item) => total + item.basePrice * item.quantity, 0);
    const requestIsVideo = () => requestCart.some((item) => item.package.startsWith("AI Video"));
    const requestIsDesign = () => requestCart.some((item) => item.package.startsWith("Foto AI"));
    const requestDurationOptions = (packageName, selectedDuration) => {
        const durations = packageName.includes("Basic") ? ["15 - 30 detik"] : packageName.includes("Standard") ? ["30 - 60 detik"] : packageName.includes("Premium") ? ["60 - 90 detik"] : ["60 - 120 detik"];
        return durations.map((duration) => `<option ${selectedDuration === duration || !selectedDuration ? "selected" : ""}>${duration}</option>`).join("");
    };

    const requestShowStep = (step) => {
        requestStep = step;
        requestCheckout.querySelectorAll("[data-request-panel]").forEach((panel) => { panel.hidden = Number(panel.dataset.requestPanel) !== step; });
        requestCheckout.querySelectorAll("[data-request-step]").forEach((item) => item.classList.toggle("is-active", Number(item.dataset.requestStep) <= step));
        requestTitle.textContent = ["Keranjang Pesanan", "Form Pemesanan", "Review Pesanan", "Invoice Pemesanan", "Pembayaran"][step - 1];
    };

    const requestRenderCart = () => {
        requestCartView.innerHTML = requestCart.map((item, index) => `<div class="request-cart-item"><strong>${item.package}</strong><span>${item.quantity} x ${requestFormatPrice(item.price)} = ${requestFormatPrice(item.price * item.quantity)}${item.discount ? ` · Diskon ${item.discount}%` : ""}</span><div class="request-quantity"><button type="button" data-request-decrease="${index}" aria-label="Kurangi jumlah ${item.package}">-</button><strong>${item.quantity}</strong><button type="button" data-request-increase="${index}" aria-label="Tambah jumlah ${item.package}">+</button></div><button type="button" data-request-remove="${index}">Hapus</button></div>`).join("");
        requestTotalView.textContent = `${requestFormatPrice(requestTotal())} · Hemat ${requestFormatPrice(requestSubtotal() - requestTotal())}`;
        requestCartTrigger.querySelector("span").textContent = requestCart.reduce((total, item) => total + item.quantity, 0);
        const drawerCart = requestDrawer.querySelector("[data-drawer-cart]");
        drawerCart.innerHTML = requestCart.map((item, index) => `<div class="request-cart-item"><strong>${item.package}</strong><span>${item.quantity} x ${requestFormatPrice(item.price)} = ${requestFormatPrice(item.price * item.quantity)}${item.discount ? ` · Diskon ${item.discount}%` : ""}</span><div class="request-quantity"><button type="button" data-request-decrease="${index}" aria-label="Kurangi jumlah ${item.package}">-</button><strong>${item.quantity}</strong><button type="button" data-request-increase="${index}" aria-label="Tambah jumlah ${item.package}">+</button></div><div class="request-item-custom"><label class="request-field">Tema Konten<input data-item-field="primary" data-item-index="${index}" value="${item.custom?.primary || ""}" placeholder="Contoh: Promo produk baru" required></label>${item.package.startsWith("AI Video") ? `<label class="request-field">Nama Produk / Jasa<input data-item-field="product" data-item-index="${index}" value="${item.custom?.product || ""}" placeholder="Contoh: Produk unggulan" required></label><label class="request-field">Durasi Video<select data-item-field="secondary" data-item-index="${index}" required>${requestDurationOptions(item.package, item.custom?.secondary)}</select></label>` : `<label class="request-field">Target Audiens<input data-item-field="secondary" data-item-index="${index}" value="${item.custom?.secondary || ""}" placeholder="Contoh: Pemilik usaha usia 25-40 tahun" required></label>`}<label class="request-field">Catatan Tambahan<textarea data-item-field="notes" data-item-index="${index}" rows="2" placeholder="Contoh: Gunakan gaya modern dan warna brand.">${item.custom?.notes || ""}</textarea></label></div><div><button type="button" data-drawer-remove="${index}">Hapus</button></div></div>`).join("");
        requestDrawer.querySelector("[data-drawer-total]").textContent = `${requestFormatPrice(requestTotal())} · Hemat ${requestFormatPrice(requestSubtotal() - requestTotal())}`;
        requestCheckout.querySelectorAll("[data-request-decrease], [data-request-increase]").forEach((button) => button.addEventListener("click", () => {
            const item = requestCart[Number(button.dataset.requestDecrease ?? button.dataset.requestIncrease)];
            if (!item) return;
            item.quantity = Math.max(1, item.quantity + (button.dataset.requestIncrease !== undefined ? 1 : -1));
            requestRenderCart();
        }));
        requestDrawer.querySelectorAll("[data-request-decrease], [data-request-increase]").forEach((button) => button.addEventListener("click", () => {
            const item = requestCart[Number(button.dataset.requestDecrease ?? button.dataset.requestIncrease)];
            if (!item) return;
            item.quantity = Math.max(1, item.quantity + (button.dataset.requestIncrease !== undefined ? 1 : -1));
            requestRenderCart();
        }));
        drawerCart.querySelectorAll("[data-item-field]").forEach((field) => field.addEventListener("input", () => { const item = requestCart[Number(field.dataset.itemIndex)]; item.custom = { ...(item.custom || {}), [field.dataset.itemField]: field.value }; }));
        drawerCart.querySelectorAll("[data-drawer-remove]").forEach((button) => button.addEventListener("click", () => { requestCart.splice(Number(button.dataset.drawerRemove), 1); requestRenderCart(); }));
        drawerCart.querySelectorAll("[data-drawer-edit]").forEach((button) => button.addEventListener("click", () => { requestRenderForm(); requestDrawer.classList.remove("is-open"); requestDrawerBackdrop.hidden = true; requestCheckout.hidden = false; document.body.style.overflow = "hidden"; requestShowStep(2); }));
        requestCartView.querySelectorAll("[data-request-remove]").forEach((button) => button.addEventListener("click", () => { requestCart.splice(Number(button.dataset.requestRemove), 1); requestRenderCart(); }));
    };

    const requestRenderForm = () => {
        const videoFields = requestIsVideo() ? `<fieldset class="request-grid"><legend class="request-field full">Detail Pesanan Video AI</legend><label class="request-field">Jumlah Video<input name="video_quantity" type="number" min="1" value="1" required></label><label class="request-field">Durasi Video<select name="video_duration"><option>15 detik</option><option>15 - 30 detik</option></select></label><label class="request-field">Tema / Konsep Video<textarea name="video_theme" rows="2" required></textarea></label><label class="request-field">Bahasa Narasi<input name="narration_language" value="Bahasa Indonesia" required></label><label class="request-field">Script Disediakan Klien?<select name="client_script"><option>Ya</option><option>Tidak</option></select></label><label class="request-field">Link Referensi<input name="video_reference" type="url"></label><label class="request-field full">Catatan Tambahan<textarea name="video_notes" rows="2"></textarea></label><label class="request-field">Upload Logo<input name="logo" type="file" accept="image/*"></label><label class="request-field">Upload Foto/Video Pendukung<input name="supporting_media" type="file" accept="image/*,video/*"></label></fieldset>` : "";
        const designFields = requestIsDesign() ? `<fieldset class="request-grid"><legend class="request-field full">Detail Pesanan Foto / Carousel</legend><label class="request-field">Jumlah Desain<input name="design_quantity" type="number" min="1" value="1" required></label><label class="request-field">Nama Produk / Jasa<input name="product_name" required></label><label class="request-field">Target Audiens<input name="target_audience" required></label><label class="request-field">Warna Brand<input name="brand_color" placeholder="Contoh: biru dan putih"></label><label class="request-field full">Link Referensi Desain<input name="design_reference" type="url"></label><label class="request-field full">Catatan Tambahan<textarea name="design_notes" rows="2"></textarea></label><label class="request-field">Upload Logo<input name="design_logo" type="file" accept="image/*"></label><label class="request-field">Upload Foto Produk<input name="product_photos" type="file" accept="image/*"></label></fieldset>` : "";
        requestProductFields.innerHTML = `<div class="request-cart"><strong>Paket Dipilih</strong>${requestCart.map((item) => `<span>${item.package} x ${item.quantity}</span>`).join("")}</div>${videoFields}${designFields}`;
    };

    const requestOpen = (packageName, price) => {
        const existing = requestCart.find((item) => item.package === packageName);
        if (existing) existing.quantity += 1; else requestCart.push({ package: packageName, ...price, quantity: 1 });
        requestRenderCart();
        pulseCart();
        requestDrawer.classList.add("is-open");
        requestDrawerBackdrop.hidden = false;
        document.body.style.overflow = "hidden";
    };

    const requestBuildReview = () => {
        const data = new FormData(requestDetails);
        const customer = data.get("customer_name");
        requestReview.innerHTML = `<div><span>Nama / Perusahaan</span><strong>${customer}</strong></div>${requestCart.map((item) => `<div><span>${item.package} (${item.quantity} item)</span><strong>${requestFormatPrice(item.price * item.quantity)}</strong></div>`).join("")}<div><span>Subtotal</span><strong>${requestFormatPrice(requestSubtotal())}</strong></div><div><span>Diskon</span><strong>- ${requestFormatPrice(requestSubtotal() - requestTotal())}</strong></div><div class="request-total"><span>Total Pembayaran</span><strong>${requestFormatPrice(requestTotal())}</strong></div>`;
    };

    const requestBuildInvoice = () => {
        requestInvoice = `INV-${new Date().toISOString().slice(0, 10).replaceAll("-", "")}-${String(Date.now()).slice(-4)}`;
        const data = new FormData(requestDetails);
        const customerName = data.get("customer_name");
        const orderLines = requestCart.map((item) => `${item.package} | Jumlah: ${item.quantity} | Harga setelah diskon: ${requestFormatPrice(item.price * item.quantity)} | Diskon: ${item.discount}% | Detail: ${Object.values(item.custom || {}).filter(Boolean).join("; ") || "Tidak ada"}`);
        const whatsappMessage = `Halo GASS, saya ${customerName} ingin memesan paket by request.\n\nNomor Invoice: ${requestInvoice}\n${orderLines.join("\n")}\n\nTotal: ${requestFormatPrice(requestTotal())}`;
        const invoiceItems = requestCart.map((item, index) => `<div class="invoice-line"><span>${index + 1}. ${item.package}<small>${Object.values(item.custom || {}).filter(Boolean).join(" | ") || "By request"}${item.discount ? ` · Diskon ${item.discount}%` : ""}</small></span><span>${item.quantity} x ${requestFormatPrice(item.price)}</span><strong>${requestFormatPrice(item.price * item.quantity)}</strong></div>`).join("");
        requestInvoiceView.innerHTML = `<div class="invoice-preview"><div class="invoice-brand"><strong>GASS</strong><span>Growth Acceleration<br>Strategic Services</span><b>INVOICE PEMESANAN</b></div><div class="invoice-meta"><span>No. Invoice<br><strong>${requestInvoice}</strong></span><span>Tanggal<br><strong>${new Date().toLocaleDateString("id-ID")}</strong></span><span>Nama / Perusahaan<br><strong>${customerName}</strong></span></div><div class="invoice-table"><div class="invoice-table-head"><span>No / Layanan</span><span>Qty x Harga</span><span>Total</span></div>${invoiceItems}</div><div class="invoice-bottom"><div><strong>Catatan Pemesanan</strong><p>Paket dibuat sesuai brief dan kustomisasi yang dipilih di keranjang.</p></div><div class="invoice-summary"><div><span>Subtotal</span><strong>${requestFormatPrice(requestSubtotal())}</strong></div><div><span>Diskon</span><strong>- ${requestFormatPrice(requestSubtotal() - requestTotal())}</strong></div><div class="invoice-total"><span>Total Pembayaran</span><strong>${requestFormatPrice(requestTotal())}</strong></div></div></div><div class="invoice-status"><span>Status Pembayaran</span><b class="request-status">Pending</b></div></div><div class="request-actions"><button type="button" data-request-download>Download PDF</button><button type="button" data-request-whatsapp>Chat WhatsApp</button></div>`;
        requestInvoiceView.querySelector("[data-request-download]").addEventListener("click", () => {
            const printWindow = window.open("", "_blank", "width=800,height=900");
            if (!printWindow) return;
            printWindow.document.write(`<html><head><title>${requestInvoice}</title><style>@page{size:A4;margin:14mm}*{box-sizing:border-box}body{font:12px Arial,sans-serif;color:#061b45;margin:0}.invoice{border:1px solid #d8dee8;padding:28px}.header{display:flex;align-items:center;gap:12px;padding-bottom:22px;border-bottom:3px solid #087bdc}.logo{font-size:28px;font-weight:800;color:#087bdc}.logo span{display:block;font-size:9px;color:#61708d;font-weight:400}.title{margin-left:auto;font-size:22px;font-weight:700}.meta{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;padding:20px 0}.meta span{color:#61708d;font-size:10px}.meta b{display:block;margin-top:5px;color:#061b45;font-size:12px}.table-head,.line{display:grid;grid-template-columns:2fr 1fr 1fr;gap:14px;padding:12px;border-bottom:1px solid #d8dee8}.table-head{background:#087bdc;color:#fff;font-weight:bold}.line small{display:block;margin-top:5px;color:#61708d}.line>span:nth-child(2),.line strong{text-align:right}.summary{display:flex;justify-content:space-between;gap:20px;margin-top:24px;padding-top:18px;border-top:2px solid #061b45}.total{font-size:19px;font-weight:bold}.status{display:inline-block;margin-top:18px;padding:8px 10px;background:#fff0bf;color:#765800;font-weight:bold}</style></head><body><div class="invoice"><div class="header"><div class="logo">GASS<span>Growth Acceleration Strategic Services</span></div><div class="title">INVOICE PEMESANAN</div></div><div class="meta"><span>No. Invoice<b>${requestInvoice}</b></span><span>Tanggal<b>${new Date().toLocaleDateString("id-ID")}</b></span><span>Nama / Perusahaan<b>${customerName}</b></span></div><div class="table-head"><span>No / Layanan</span><span>Qty x Harga</span><span>Total</span></div>${requestCart.map((item, index) => `<div class="line"><span>${index + 1}. ${item.package}<small>${Object.values(item.custom || {}).filter(Boolean).join(" | ") || "By request"}</small></span><span>${item.quantity} x ${requestFormatPrice(item.price)}</span><strong>${requestFormatPrice(item.price * item.quantity)}</strong></div>`).join("")}<div class="summary"><span>Total Pembayaran</span><strong class="total">${requestFormatPrice(requestTotal())}</strong></div><div class="status">Status Pembayaran: Pending</div></div></body></html>`);
            const printLogo = printWindow.document.querySelector(".logo");
            printLogo.insertAdjacentHTML("afterbegin", '<img src="/LOGO%20GASS%20BULAT.png" alt="GASS">');
            printLogo.style.display = "flex";
            printLogo.style.alignItems = "center";
            printLogo.style.gap = "10px";
            const printLogoImage = printLogo.querySelector("img");
            printLogoImage.style.cssText = "width:56px;height:56px;border-radius:50%;object-fit:cover;";
            printWindow.document.querySelector(".status")?.remove();
            let invoicePrinted = false;
            const printInvoice = () => {
                if (invoicePrinted) return;
                invoicePrinted = true;
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            };
            printLogoImage.addEventListener("load", printInvoice, { once: true });
            printLogoImage.addEventListener("error", printInvoice, { once: true });
            if (printLogoImage.complete) printInvoice();
        });
        requestInvoiceView.querySelector("[data-request-whatsapp]").addEventListener("click", () => { window.open(`https://wa.me/6285890007359?text=${encodeURIComponent(whatsappMessage)}`, "_blank"); });
    };

    document.addEventListener("click", (event) => {
        const button = event.target.closest("[data-order-package]");
        if (!button) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        requestOpen(button.dataset.orderPackage, requestPackages[button.dataset.orderPackage]);
    }, true);
    requestCartTrigger.addEventListener("click", () => { requestRenderCart(); requestDrawer.classList.add("is-open"); requestDrawerBackdrop.hidden = false; document.body.style.overflow = "hidden"; });
    const closeRequestDrawer = () => { requestDrawer.classList.remove("is-open"); requestDrawerBackdrop.hidden = true; document.body.style.overflow = ""; };
    requestDrawer.querySelector(".request-drawer-close").addEventListener("click", closeRequestDrawer);
    requestDrawerBackdrop.addEventListener("click", closeRequestDrawer);
    requestDrawer.querySelector("[data-drawer-continue]").addEventListener("click", () => { if (!requestCart.length || !drawerCustomer.checkValidity() || !requestDrawer.querySelectorAll("[data-item-field][required]").length || [...requestDrawer.querySelectorAll("[data-item-field][required]")].some((field) => !field.checkValidity())) { drawerCustomer.reportValidity(); return; } requestDetails.elements.customer_name.value = drawerCustomer.value; requestBuildInvoice(); closeRequestDrawer(); requestCheckout.hidden = false; document.body.style.overflow = "hidden"; requestShowStep(4); });
    requestCheckout.querySelector(".request-close").addEventListener("click", () => { requestCheckout.hidden = true; document.body.style.overflow = ""; });
    requestCheckout.querySelector("[data-request-continue]").addEventListener("click", () => { requestCheckout.hidden = true; document.body.style.overflow = ""; document.querySelector(".package-grid")?.scrollIntoView({ behavior: "smooth" }); });
    requestDetails.addEventListener("submit", (event) => { event.preventDefault(); if (!requestDetails.reportValidity()) return; requestBuildReview(); requestShowStep(3); });
    requestCheckout.querySelectorAll("[data-request-back]").forEach((button) => button.addEventListener("click", () => requestShowStep(Number(button.dataset.requestBack))));
    requestCheckout.querySelectorAll("[data-request-next]").forEach((button) => button.addEventListener("click", () => { const next = Number(button.dataset.requestNext); if (next === 2) { requestRenderForm(); requestShowStep(2); } if (next === 4) { requestBuildInvoice(); requestShowStep(4); } if (next === 5) requestInvoiceView.querySelector("[data-request-whatsapp]")?.click(); }));
    requestCheckout.querySelector("[data-request-paid]").addEventListener("click", () => { requestPaidState.hidden = false; requestPaidState.innerHTML = `<div><span>Status Pembayaran</span><strong class="request-status">Menunggu Verifikasi</strong></div><p>Konfirmasi pembayaran sudah dicatat. Pesanan akan diproses setelah diverifikasi tim GASS.</p>`; });
}
