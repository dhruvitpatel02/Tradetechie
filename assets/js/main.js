/**
 * TradeTechie - Main JavaScript
 */

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap form validation
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});

// Password strength indicator
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    return strength;
}

// Display password strength
function displayPasswordStrength(inputId, displayId) {
    const input = document.getElementById(inputId);
    const display = document.getElementById(displayId);
    
    if (input && display) {
        input.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            const strengthColor = ['danger', 'warning', 'info', 'primary', 'success'];
            
            if (this.value.length > 0) {
                display.innerHTML = `<small class="text-${strengthColor[strength - 1]}">Password Strength: ${strengthText[strength - 1]}</small>`;
            } else {
                display.innerHTML = '';
            }
        });
    }
}

// Confirm password match
function checkPasswordMatch(password1Id, password2Id, displayId) {
    const pass1 = document.getElementById(password1Id);
    const pass2 = document.getElementById(password2Id);
    const display = document.getElementById(displayId);
    
    if (pass1 && pass2 && display) {
        pass2.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (pass1.value === this.value) {
                    display.innerHTML = '<small class="text-success"><i class="bi bi-check-circle"></i> Passwords match</small>';
                } else {
                    display.innerHTML = '<small class="text-danger"><i class="bi bi-x-circle"></i> Passwords do not match</small>';
                }
            } else {
                display.innerHTML = '';
            }
        });
    }
}

// Format number as currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(amount);
}

// Format number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Smooth scroll to element
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Show loading spinner
function showLoading() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner-overlay';
    spinner.id = 'loadingSpinner';
    spinner.innerHTML = '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
    document.body.appendChild(spinner);
}

// Hide loading spinner
function hideLoading() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.remove();
    }
}


// Dark Mode Toggle
const themeToggle = document.getElementById('themeToggle');
const currentTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-theme', currentTheme);

if (themeToggle) {
    themeToggle.textContent = currentTheme === 'light' ? '\ud83c\udf19' : '\u2600\ufe0f';
    themeToggle.addEventListener('click', () => {
        const theme = document.documentElement.getAttribute('data-theme');
        const newTheme = theme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        themeToggle.textContent = newTheme === 'light' ? '\ud83c\udf19' : '\u2600\ufe0f';
    });
}

// Auto-save for notes
let autoSaveTimer;
const noteContent = document.getElementById('note_content');
const autoSaveIndicator = document.createElement('div');
autoSaveIndicator.className = 'auto-save-indicator';
autoSaveIndicator.textContent = 'Saved';
document.body.appendChild(autoSaveIndicator);

if (noteContent) {
    noteContent.addEventListener('input', () => {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            localStorage.setItem('note_draft_' + noteContent.dataset.noteId, noteContent.value);
            autoSaveIndicator.classList.add('show');
            setTimeout(() => autoSaveIndicator.classList.remove('show'), 2000);
        }, 2000);
    });
    
    // Load draft on page load
    const draft = localStorage.getItem('note_draft_' + noteContent.dataset.noteId);
    if (draft && !noteContent.value) {
        noteContent.value = draft;
    }
}

// Live price updates for watchlist
function updateWatchlistPrices() {
    const priceElements = document.querySelectorAll('[data-symbol]');
    if (priceElements.length === 0) return;
    
    priceElements.forEach(el => {
        const currentPrice = parseFloat(el.querySelector('.price-value')?.textContent.replace('₹', '').replace(',', ''));
        if (currentPrice) {
            const change = (Math.random() - 0.5) * 2;
            const newPrice = currentPrice + change;
            const changePercent = (change / currentPrice) * 100;
            
            if (el.querySelector('.price-value')) {
                el.querySelector('.price-value').textContent = '₹' + newPrice.toFixed(2);
            }
            if (el.querySelector('.change-percent')) {
                const changeEl = el.querySelector('.change-percent');
                changeEl.textContent = (changePercent >= 0 ? '+' : '') + changePercent.toFixed(2) + '%';
                changeEl.className = 'change-percent ' + (changePercent >= 0 ? 'price-up' : 'price-down');
            }
        }
    });
}

if (document.querySelector('[data-symbol]')) {
    setInterval(updateWatchlistPrices, 30000);
}

// Progress tracking
function markComplete(contentId) {
    fetch('<?php echo SITE_URL; ?>learn/mark_complete.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'content_id=' + contentId + '&csrf_token=' + document.querySelector('[name=csrf_token]')?.value
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const btn = document.querySelector('[data-content="' + contentId + '"]');
            if (btn) {
                btn.textContent = '✓ Completed';
                btn.classList.add('btn-success');
                btn.disabled = true;
            }
            updateProgressBar();
        }
    });
}

function updateProgressBar() {
    const total = document.querySelectorAll('[data-content]').length;
    const completed = document.querySelectorAll('[data-content].btn-success').length;
    const percent = total > 0 ? (completed / total) * 100 : 0;
    const progressFill = document.querySelector('.progress-fill');
    if (progressFill) {
        progressFill.style.width = percent + '%';
    }
}

// Initialize progress bar on page load
document.addEventListener('DOMContentLoaded', updateProgressBar);

// Tag input helper
const tagInput = document.getElementById('tags');
if (tagInput) {
    const suggestions = ['strategy', 'long-term', 'short-term', 'swing', 'risk', 'bullish', 'bearish'];
    const datalist = document.createElement('datalist');
    datalist.id = 'tag-suggestions';
    suggestions.forEach(tag => {
        const option = document.createElement('option');
        option.value = tag;
        datalist.appendChild(option);
    });
    tagInput.setAttribute('list', 'tag-suggestions');
    tagInput.parentNode.appendChild(datalist);
}
