// ══════════════════════════════════
// GymFit — Main JavaScript
// ══════════════════════════════════

// ── Hamburger Menu ──
function toggleMenu() {
    const menu = document.getElementById('mobileMenu');
    const hamburger = document.getElementById('hamburger');
    menu.classList.toggle('open');
    hamburger.classList.toggle('active');
}

// Ferme le menu si on clique dehors
document.addEventListener('click', function(e) {
    const menu = document.getElementById('mobileMenu');
    const hamburger = document.getElementById('hamburger');
    if (!menu || !hamburger) return;
    if (!menu.contains(e.target) && !hamburger.contains(e.target)) {
        menu.classList.remove('open');
        hamburger.classList.remove('active');
    }
});

// ── Active nav link ──
document.addEventListener('DOMContentLoaded', function() {
    const currentRoute = new URLSearchParams(window.location.search).get('route') || 'home';
    const navLinks = document.querySelectorAll('.nav-links a, .nav-mobile-menu a');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes('route=' + currentRoute)) {
            link.classList.add('active');
        }
    });
});

// ── Billing Toggle Monthly/Annual ──
const billingToggle = document.getElementById('billing-toggle');
if (billingToggle) {
    billingToggle.addEventListener('change', function() {
        const isAnnual = this.checked;
        document.querySelectorAll('.price-monthly').forEach(el => {
            el.style.display = isAnnual ? 'none' : 'flex';
        });
        document.querySelectorAll('.price-annual').forEach(el => {
            el.style.display = isAnnual ? 'flex' : 'none';
        });
        document.getElementById('label-monthly').classList.toggle('active', !isAnnual);
        document.getElementById('label-annual').classList.toggle('active', isAnnual);
    });
}

// ── Plan Selector ──
function selectPlan(input) {
    document.querySelectorAll('.plan-option').forEach(el => {
        el.classList.remove('selected');
    });
    input.closest('.plan-option').classList.add('selected');
}

// ── Password Strength ──
function checkPasswordStrength(value) {
    const bars = [
        document.getElementById('bar1'),
        document.getElementById('bar2'),
        document.getElementById('bar3'),
        document.getElementById('bar4')
    ];
    const label = document.getElementById('pwd-label');
    if (!bars[0]) return;

    bars.forEach(b => { b.className = 'pwd-bar'; });

    let strength = 0;
    if (value.length >= 8)              strength++;
    if (/[A-Z]/.test(value))            strength++;
    if (/[0-9]/.test(value))            strength++;
    if (/[^A-Za-z0-9]/.test(value))     strength++;

    const levels = ['weak', 'weak', 'medium', 'strong'];
    const labels = [
        'Too short',
        'Weak — add uppercase letters',
        'Medium — add a number',
        'Strong password ✓'
    ];

    for (let i = 0; i < strength; i++) {
        bars[i].classList.add(levels[strength - 1]);
    }

    if (value.length > 0) {
        label.textContent = labels[strength - 1];
        label.style.color = strength >= 3 ? '#27ae60'
                          : strength >= 2 ? '#f39c12'
                          : '#e74c3c';
    } else {
        label.textContent = 'Minimum 8 characters';
        label.style.color = '';
    }
}