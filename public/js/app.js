document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const navToggle = document.querySelector('[data-nav-toggle]');
    const adminToggle = document.querySelector('[data-admin-toggle]');
    const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');

    navToggle?.addEventListener('click', () => {
        const open = body.classList.toggle('nav-open');
        navToggle.setAttribute('aria-expanded', String(open));
    });

    const closeSidebar = () => {
        body.classList.remove('sidebar-open');
        adminToggle?.setAttribute('aria-expanded', 'false');
    };

    adminToggle?.addEventListener('click', () => {
        const open = body.classList.toggle('sidebar-open');
        adminToggle.setAttribute('aria-expanded', String(open));
    });

    sidebarBackdrop?.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            body.classList.remove('nav-open');
            navToggle?.setAttribute('aria-expanded', 'false');
            closeSidebar();
        }
    });

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-lock-submit]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submit = form.querySelector('[type="submit"]');

            if (submit) {
                submit.disabled = true;
                submit.textContent = submit.dataset.loadingText || 'Please wait...';
            }
        });
    });

    const compareForm = document.querySelector('[data-compare-form]');

    if (compareForm) {
        const boxes = [...compareForm.querySelectorAll('input[type="checkbox"]')];
        const counter = compareForm.querySelector('[data-compare-count]');
        const warning = compareForm.querySelector('[data-compare-warning]');
        const submit = compareForm.querySelector('[data-compare-submit]');

        const updateCompare = () => {
            const selected = boxes.filter((box) => box.checked).length;

            if (counter) {
                counter.textContent = String(selected);
            }

            boxes.forEach((box) => {
                const unavailable = selected >= 3 && !box.checked;
                box.disabled = unavailable;
                box.closest('.compare-option')?.classList.toggle('is-disabled', unavailable);
            });

            if (warning) {
                warning.hidden = selected < 3;
            }

            if (submit) {
                submit.disabled = selected === 0;
            }
        };

        boxes.forEach((box) => box.addEventListener('change', updateCompare));
        updateCompare();
    }

    const checkout = document.querySelector('[data-checkout]');

    if (checkout) {
        const variant = checkout.querySelector('[data-variant]');
        const quantity = checkout.querySelector('[data-quantity]');
        const delivery = checkout.querySelector('[data-delivery]');
        const payment = checkout.querySelector('[data-payment]');
        const addressGroup = checkout.querySelector('[data-address-group]');
        const address = checkout.querySelector('[name="customer_address"]');
        const bankPanel = checkout.querySelector('[data-bank-panel]');
        const reference = checkout.querySelector('[name="payment_reference"]');
        const priceNode = document.querySelector('[data-product-price]');
        const stockNode = document.querySelector('[data-product-stock]');
        const subtotalNode = checkout.querySelector('[data-subtotal]');
        const deliveryNode = checkout.querySelector('[data-delivery-fee]');
        const totalNode = checkout.querySelector('[data-total]');
        const submit = checkout.querySelector('[type="submit"]');
        const basePrice = Number(checkout.dataset.basePrice || 0);
        const baseStock = Number(checkout.dataset.baseStock || 0);
        const deliveryFee = Number(checkout.dataset.deliveryFee || 0);
        const currency = new Intl.NumberFormat('en-LK');

        const selectedData = () => {
            if (!variant || !variant.value) {
                return { price: basePrice, stock: baseStock };
            }

            const option = variant.options[variant.selectedIndex];
            return {
                price: Number(option.dataset.price || basePrice),
                stock: Number(option.dataset.stock || 0),
            };
        };

        const updateCheckout = () => {
            const selected = selectedData();
            const requested = Math.max(1, Number(quantity?.value || 1));
            const fee = delivery?.value === 'delivery' ? deliveryFee : 0;
            const subtotal = selected.price * requested;

            if (quantity) {
                quantity.max = String(Math.min(10, selected.stock || 1));
            }

            if (priceNode) {
                priceNode.textContent = `Rs. ${currency.format(selected.price)}`;
            }

            if (stockNode) {
                stockNode.textContent = selected.stock > 0
                    ? `${selected.stock} unit${selected.stock === 1 ? '' : 's'} available`
                    : 'Currently out of stock';
            }

            if (subtotalNode) {
                subtotalNode.textContent = `Rs. ${currency.format(subtotal)}`;
            }

            if (deliveryNode) {
                deliveryNode.textContent = `Rs. ${currency.format(fee)}`;
            }

            if (totalNode) {
                totalNode.textContent = `Rs. ${currency.format(subtotal + fee)}`;
            }

            if (addressGroup && address) {
                const needsAddress = delivery?.value === 'delivery';
                addressGroup.hidden = !needsAddress;
                address.required = needsAddress;
            }

            if (bankPanel && reference) {
                const bankTransfer = payment?.value === 'bank_transfer';
                bankPanel.hidden = !bankTransfer;
                reference.required = bankTransfer;
            }

            if (submit) {
                submit.disabled = selected.stock < requested;
            }
        };

        [variant, quantity, delivery, payment].forEach((field) => {
            field?.addEventListener('change', updateCheckout);
            field?.addEventListener('input', updateCheckout);
        });

        updateCheckout();
    }

    document.querySelectorAll('[data-image-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const preview = document.querySelector(input.dataset.imageInput);
            const file = input.files?.[0];

            if (preview && file) {
                preview.src = URL.createObjectURL(file);
                preview.hidden = false;
            }
        });
    });
});
