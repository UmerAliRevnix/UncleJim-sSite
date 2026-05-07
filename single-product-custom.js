(function () {
    var isProductTransitioning = false;

    function initPackCards(scope) {
        (scope || document).querySelectorAll('.custom-pack-card').forEach(function (card) {
            if (card.dataset.packBound === '1') {
                return;
            }

            card.dataset.packBound = '1';

            card.addEventListener('click', function (e) {
                if (e.target.tagName.toLowerCase() === 'input') {
                    return;
                }

                document.querySelectorAll('.custom-pack-card').forEach(function (c) {
                    c.classList.remove('custom-pack-card--selected');
                });
                this.classList.add('custom-pack-card--selected');

                var input = this.querySelector('input');
                if (input) {
                    input.checked = true;
                }

                // Update the "Pack Size: XXX" label
                var titleEl = this.querySelector('.custom-pack-card__title');
                var labelEl = document.getElementById('dynamic-pack-label');
                if (titleEl && labelEl) {
                    labelEl.innerText = titleEl.innerText;
                }

                // Update the Main Price Display
                var price = this.dataset.price;
                var oldPrice = this.dataset.oldPrice;
                var discount = this.dataset.discount;

                var mainSalePriceEl = document.getElementById('custom-main-sale-price');
                var mainRegPriceEl = document.getElementById('custom-main-reg-price');
                var mainDiscountEl = document.getElementById('custom-main-discount');

                if (mainSalePriceEl && price) {
                    mainSalePriceEl.innerText = '$' + parseFloat(price).toFixed(2);
                }

                if (mainRegPriceEl) {
                    if (oldPrice && parseFloat(oldPrice) > parseFloat(price)) {
                        mainRegPriceEl.innerText = '$' + parseFloat(oldPrice).toFixed(2);
                        mainRegPriceEl.style.display = 'inline';
                    } else {
                        mainRegPriceEl.innerText = '';
                        mainRegPriceEl.style.display = 'none';
                    }
                }

                if (mainDiscountEl) {
                    if (discount) {
                        mainDiscountEl.innerText = discount;
                        mainDiscountEl.style.display = 'inline-block';
                    } else {
                        mainDiscountEl.innerText = '';
                        mainDiscountEl.style.display = 'none';
                    }
                }
            });
        });
    }

    function setActiveSwatchByUrl(url) {
        var normalized = new URL(url, window.location.origin).pathname.replace(/\/$/, '');
        document.querySelectorAll('.custom-product-swatches .custom-product-swatch').forEach(function (swatch) {
            var swatchPath = new URL(swatch.href, window.location.origin).pathname.replace(/\/$/, '');
            swatch.classList.toggle('is-active', swatchPath === normalized);
        });
    }

    function reinitWooGallery() {
        if (!window.jQuery) {
            return;
        }

        var $ = window.jQuery;
        var $galleries = $('.custom-product__gallery .woocommerce-product-gallery');

        if (!$galleries.length) {
            return;
        }

        if (typeof $.fn.wc_product_gallery === 'function') {
            $galleries.each(function () {
                var $gallery = $(this);
                $gallery.wc_product_gallery();
            });
        }
    }

    function initGallerySlideTrigger(scope) {
        (scope || document).querySelectorAll('.woocommerce-product-gallery').forEach(function (gallery) {
            if (gallery.dataset.slideTriggerBound === '1') {
                return;
            }

            gallery.dataset.slideTriggerBound = '1';

            gallery.addEventListener('click', function (e) {
                if (!e.target.closest('.woocommerce-product-gallery__image.flex-active-slide')) {
                    return;
                }

                var trigger = gallery.querySelector('.woocommerce-product-gallery__trigger');
                if (!trigger) {
                    return;
                }

                e.preventDefault();
                trigger.click();
            });
        });
    }

    function initMobileThumbSync(scope) {
        (scope || document).querySelectorAll('.custom-product__gallery .woocommerce-product-gallery').forEach(function (gallery) {
            if (gallery.dataset.thumbSyncBound === '1') {
                return;
            }

            var thumbsNav = gallery.querySelector('.flex-control-nav.flex-control-thumbs');
            if (!thumbsNav) {
                return;
            }

            gallery.dataset.thumbSyncBound = '1';

            var isTicking = false;
            var lastSyncedIndex = -1;
            var touchStartX = 0;
            var touchStartY = 0;

            function getThumbImages() {
                return Array.prototype.slice.call(thumbsNav.querySelectorAll('li img'));
            }

            function getActiveThumbIndex() {
                var items = getThumbImages();
                if (!items.length) {
                    return -1;
                }

                var activeIndex = items.findIndex(function (img) {
                    return img.classList.contains('flex-active');
                });

                if (activeIndex >= 0) {
                    return activeIndex;
                }

                return lastSyncedIndex >= 0 ? lastSyncedIndex : 0;
            }

            function centerThumbByIndex(index) {
                var items = getThumbImages();
                var target = items[index];
                if (!target) {
                    return;
                }

                var navCenter = thumbsNav.clientWidth / 2;
                var targetCenter = target.offsetLeft + (target.offsetWidth / 2);
                var left = Math.max(0, targetCenter - navCenter);

                thumbsNav.scrollTo({
                    left: left,
                    behavior: 'smooth'
                });
            }

            function selectThumbByIndex(index) {
                var items = getThumbImages();
                var target = items[index];
                if (!target) {
                    return;
                }

                if (index === lastSyncedIndex && target.classList.contains('flex-active')) {
                    return;
                }

                lastSyncedIndex = index;
                target.click();
                centerThumbByIndex(index);
            }

            function syncMainImageFromThumbPosition() {
                isTicking = false;

                if (window.innerWidth >= 992) {
                    return;
                }

                var items = thumbsNav.querySelectorAll('li img');
                if (!items.length) {
                    return;
                }

                var navRect = thumbsNav.getBoundingClientRect();
                var navCenterX = navRect.left + (navRect.width / 2);
                var closestIndex = 0;
                var closestDistance = Number.MAX_SAFE_INTEGER;

                items.forEach(function (img, index) {
                    var rect = img.getBoundingClientRect();
                    var centerX = rect.left + (rect.width / 2);
                    var distance = Math.abs(centerX - navCenterX);
                    if (distance < closestDistance) {
                        closestDistance = distance;
                        closestIndex = index;
                    }
                });

                if (closestIndex === lastSyncedIndex) {
                    return;
                }

                selectThumbByIndex(closestIndex);
            }

            function queueSync() {
                if (isTicking) {
                    return;
                }
                isTicking = true;
                window.requestAnimationFrame(syncMainImageFromThumbPosition);
            }

            function moveMainImageByStep(step) {
                if (window.innerWidth >= 992) {
                    return;
                }

                var items = getThumbImages();
                if (!items.length) {
                    return;
                }

                var current = getActiveThumbIndex();
                if (current < 0) {
                    current = 0;
                }

                var next = Math.min(items.length - 1, Math.max(0, current + step));
                if (next === current) {
                    return;
                }

                selectThumbByIndex(next);
            }

            thumbsNav.addEventListener('scroll', queueSync, { passive: true });
            thumbsNav.addEventListener('touchmove', queueSync, { passive: true });

            var viewport = gallery.querySelector('.flex-viewport');
            if (viewport) {
                viewport.addEventListener('touchstart', function (e) {
                    if (!e.touches || !e.touches.length) {
                        return;
                    }

                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                }, { passive: true });

                viewport.addEventListener('touchend', function (e) {
                    if (!e.changedTouches || !e.changedTouches.length) {
                        return;
                    }

                    var dx = e.changedTouches[0].clientX - touchStartX;
                    var dy = e.changedTouches[0].clientY - touchStartY;

                    if (Math.abs(dx) < 30 || Math.abs(dx) <= Math.abs(dy)) {
                        return;
                    }

                    moveMainImageByStep(dx < 0 ? 1 : -1);
                }, { passive: true });
            }

            // Keep the thumbnail strip aligned when Woo updates the active thumb from main-image interactions.
            var activeObserver = new MutationObserver(function () {
                if (window.innerWidth >= 992) {
                    return;
                }

                var activeIndex = getActiveThumbIndex();
                if (activeIndex < 0) {
                    return;
                }

                lastSyncedIndex = activeIndex;
                centerThumbByIndex(activeIndex);
            });

            activeObserver.observe(thumbsNav, {
                subtree: true,
                attributes: true,
                attributeFilter: ['class']
            });

            window.addEventListener('resize', queueSync, { passive: true });

            // Initial sync for mobile first paint.
            queueSync();
        });
    }

    async function swapProductContent(url, shouldPushState) {
        if (isProductTransitioning) {
            return;
        }

        var currentContainer = document.querySelector('.custom-product');
        if (!currentContainer) {
            window.location.href = url;
            return;
        }

        isProductTransitioning = true;
        currentContainer.style.opacity = '0.6';

        try {
            var response = await fetch(url, {
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch product content');
            }

            var html = await response.text();
            var nextDocument = new DOMParser().parseFromString(html, 'text/html');
            var nextContainer = nextDocument.querySelector('.custom-product');

            if (!nextContainer) {
                throw new Error('Product container not found in response');
            }

            // Keep swatches static like variants: preserve existing node/order and only update active state.
            var existingSwatches = currentContainer.querySelector('.custom-product-swatches');
            var incomingSwatches = nextContainer.querySelector('.custom-product-swatches');
            if (existingSwatches && incomingSwatches) {
                var existingHeading = existingSwatches.querySelector('.custom-product-swatch-heading');
                var incomingHeading = incomingSwatches.querySelector('.custom-product-swatch-heading');

                if (existingHeading && incomingHeading) {
                    existingHeading.innerHTML = incomingHeading.innerHTML;
                } else if (existingHeading && !incomingHeading) {
                    existingHeading.remove();
                } else if (!existingHeading && incomingHeading) {
                    existingSwatches.insertBefore(incomingHeading.cloneNode(true), existingSwatches.firstChild);
                }

                incomingSwatches.replaceWith(existingSwatches);
            }

            currentContainer.replaceWith(nextContainer);

            if (shouldPushState) {
                window.history.pushState({ productUrl: url }, '', url);
            }

            initPackCards(document);
            initSwatchNavigation(document);
            setActiveSwatchByUrl(url);
            reinitWooGallery();
            initGallerySlideTrigger(document);
            initMobileThumbSync(document);
        } catch (err) {
            window.location.href = url;
        } finally {
            isProductTransitioning = false;
            var updated = document.querySelector('.custom-product');
            if (updated) {
                updated.style.opacity = '1';
            }
        }
    }

    function initSwatchNavigation(scope) {
        (scope || document).querySelectorAll('.custom-product-swatches .custom-product-swatch').forEach(function (swatch) {
            if (swatch.dataset.swatchBound === '1') {
                return;
            }

            swatch.dataset.swatchBound = '1';

            swatch.addEventListener('click', function (e) {
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) {
                    return;
                }

                e.preventDefault();

                var targetUrl = this.href;
                if (!targetUrl) {
                    return;
                }

                setActiveSwatchByUrl(targetUrl);
                swapProductContent(targetUrl, true);
            });
        });
    }

    window.addEventListener('popstate', function () {
        swapProductContent(window.location.href, false);
    });

    document.addEventListener('DOMContentLoaded', function () {
        initPackCards(document);
        initSwatchNavigation(document);
        initGallerySlideTrigger(document);
        initMobileThumbSync(document);
    });
})();