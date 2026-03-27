

document.addEventListener('DOMContentLoaded', function () {
    console.log('✨ MiniEvent loaded');

    // Initialize modules
    initNavbar();
    initMobileMenu();
    initForms();
    initAlerts();
    initScrollAnimations();
    initCardEffects();
});

/**
 * Navbar scroll effect
 */
function initNavbar() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        // Add shadow on scroll
        if (currentScroll > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    }, { passive: true });
}

/*
 Mobile menu toggle
 */
function initMobileMenu() {
    const toggle = document.getElementById('mobileMenuToggle');
    const menu = document.getElementById('navMenu');

    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
        menu.classList.toggle('show');

        // Toggle icon
        const icon = toggle.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
            const icon = toggle.querySelector('i');
            if (icon) {
                icon.classList.add('fa-bars');
                icon.classList.remove('fa-times');
            }
        }
    });

    // Close menu when clicking a link
    menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.remove('show');
            const icon = toggle.querySelector('i');
            if (icon) {
                icon.classList.add('fa-bars');
                icon.classList.remove('fa-times');
            }
        });
    });
}

/*
 Form validation and enhancements
 */
// function initForms() {
//     // const forms = document.querySelectorAll('form');
//     // On sélectionne UNIQUEMENT les formulaires qui n'ont pas de confirmation native
//     const forms = document.querySelectorAll('form:not([onsubmit*="confirm"])');

//     forms.forEach(form => {
//         const inputs = form.querySelectorAll('input, textarea, select');

//         // Real-time validation
//         inputs.forEach(input => {
//             // Add focus animation
//             input.addEventListener('focus', function () {
//                 this.parentElement.classList.add('focused');
//             });

//             input.addEventListener('blur', function () {
//                 this.parentElement.classList.remove('focused');
//                 validateField(this);
//             });

//             // Clear error on input
//             input.addEventListener('input', function () {
//                 if (this.classList.contains('error')) {
//                     clearFieldError(this);
//                 }
//             });
//         });

//         // Form submission
//         form.addEventListener('submit', function (e) {

//             let isValid = true;

//             inputs.forEach(input => {
//                 if (!validateField(input)) {
//                     isValid = false;
//                 }
//             });

//             if (!isValid) {
//                 e.preventDefault();
//                 showNotification('Veuillez corriger les erreurs dans le formulaire', 'error');

//                 // Focus first error
//                 const firstError = form.querySelector('.error');
//                 if (firstError) {
//                     firstError.focus();
//                     firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
//                 }
//             } else {
//                 // Show loading state
//                 const submitBtn = form.querySelector('button[type="submit"]');
//                 if (submitBtn) {
//                     /*submitBtn.disabled = true;*/
//                     submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Chargement...</span>';
//                 }
//             }
//         });
//     });
// }



/*
  Validate a field
 */
function validateField(field) {
    if (!field.required && !field.value.trim()) {
        clearFieldError(field);
        return true;
    }

    let isValid = true;
    let message = '';

    // Required check
    if (field.required && !field.value.trim()) {
        isValid = false;
        message = 'Ce champ est obligatoire';
    }
    // Email validation
    else if (field.type === 'email' && field.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            isValid = false;
            message = 'Adresse email invalide';
        }
    }
    // Phone validation
    else if (field.name === 'phone' && field.value) {
        const phoneRegex = /^[0-9+\s\-()]{8,20}$/;
        if (!phoneRegex.test(field.value)) {
            isValid = false;
            message = 'Numéro de téléphone invalide';
        }
    }
    // Min length
    else if (field.minLength && field.value.length < field.minLength) {
        isValid = false;
        message = `Minimum ${field.minLength} caractères`;
    }

    if (isValid) {
        clearFieldError(field);
    } else {
        showFieldError(field, message);
    }

    return isValid;
}

/*
 Show field error
 */
function showFieldError(field, message) {
    clearFieldError(field);

    field.classList.add('error');
    field.style.borderColor = 'var(--danger)';

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error-message';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    errorDiv.style.cssText = `
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        animation: slideDown 0.3s ease;
    `;

    field.parentNode.appendChild(errorDiv);
}

/*
 Clear field error
 */
function clearFieldError(field) {
    field.classList.remove('error');
    field.style.borderColor = '';

    const error = field.parentNode.querySelector('.field-error-message');
    if (error) {
        error.remove();
    }
}

/*
 Auto-dismiss alerts
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(alert => {
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideUp 0.4s ease forwards';
                setTimeout(() => alert.remove(), 400);
            }
        }, 5000);

        // Click to dismiss
        alert.style.cursor = 'pointer';
        alert.addEventListener('click', () => {
            alert.style.animation = 'slideUp 0.4s ease forwards';
            setTimeout(() => alert.remove(), 400);
        });
    });
}

/*
Scroll animations for elements
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.event-card, .stat-card, .info-card');

    if (!animatedElements.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                entry.target.style.animation = `fadeInUp 0.6s ease ${index * 0.1}s forwards`;
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
}

/*
Card hover effects
 */
function initCardEffects() {
    const cards = document.querySelectorAll('.event-card, .stat-card');

    cards.forEach(card => {
        card.addEventListener('mouseenter', function (e) {
            this.style.transform = 'translateY(-8px)';
        });

        card.addEventListener('mouseleave', function (e) {
            this.style.transform = '';
        });
    });
}

/*
Show notification toast
 */
function showNotification(message, type = 'info') {
    // Remove existing
    const existing = document.querySelector('.notification-toast');
    if (existing) existing.remove();

    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };

    const notification = document.createElement('div');
    notification.className = 'notification-toast';
    notification.innerHTML = `
        <i class="fas fa-${icons[type] || icons.info}"></i>
        <span>${message}</span>
    `;

    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 24px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--primary)'};
        color: white;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        animation: slideInRight 0.4s ease;
        max-width: 400px;
        font-weight: 500;
    `;

    document.body.appendChild(notification);

    // Auto remove
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s ease forwards';
        setTimeout(() => notification.remove(), 400);
    }, 4000);
}

/*
Update file input label
 */
function updateFileName(input) {
    const label = document.getElementById('file-label-text');
    if (label && input.files && input.files.length > 0) {
        label.textContent = input.files[0].name;
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .form-group.focused label {
        color: var(--primary);
    }
    
    .error {
        border-color: var(--danger) !important;
    }
`;
document.head.appendChild(style);