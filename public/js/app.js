// Theme Management
const themeToggle = document.getElementById('themeToggle');
const currentTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-theme', currentTheme);

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const theme = document.documentElement.getAttribute('data-theme');
        const newTheme = theme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        themeToggle.textContent = newTheme === 'light' ? '🌙' : '☀️';
    });
    themeToggle.textContent = currentTheme === 'light' ? '🌙' : '☀️';
}

// Auto-save for notes
let autoSaveTimer;
const noteContent = document.getElementById('noteContent');

if (noteContent) {
    noteContent.addEventListener('input', () => {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            const data = {
                content: noteContent.value,
                noteId: noteContent.dataset.noteId
            };
            fetch('/api/notes/autosave', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
        }, 2000);
    });
}

// Live price updates
function updatePrices() {
    const priceElements = document.querySelectorAll('[data-symbol]');
    if (priceElements.length === 0) return;
    
    const symbols = Array.from(priceElements).map(el => el.dataset.symbol);
    
    fetch('/api/stocks/prices', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({symbols})
    })
    .then(res => res.json())
    .then(data => {
        data.forEach(stock => {
            const el = document.querySelector(`[data-symbol="${stock.symbol}"]`);
            if (el) {
                el.querySelector('.price').textContent = `₹${stock.price}`;
                const change = el.querySelector('.change');
                change.textContent = `${stock.change >= 0 ? '+' : ''}${stock.change}%`;
                change.className = `change ${stock.change >= 0 ? 'price-up' : 'price-down'}`;
            }
        });
    });
}

if (document.querySelector('[data-symbol]')) {
    setInterval(updatePrices, 30000);
}

// Modal Management
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Tab Management
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.dataset.tab;
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(target).classList.add('active');
    });
});

// Search with debounce
let searchTimer;
const searchInput = document.getElementById('searchInput');

if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const query = e.target.value;
            if (query.length < 2) return;
            
            fetch(`/api/search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    displaySearchResults(data);
                });
        }, 300);
    });
}

function displaySearchResults(results) {
    const container = document.getElementById('searchResults');
    if (!container) return;
    
    container.innerHTML = results.map(item => `
        <div class="card">
            <h4>${item.title}</h4>
            <p>${item.description}</p>
            <a href="${item.url}" class="btn btn-primary">View</a>
        </div>
    `).join('');
}

// Chart initialization
function initChart(elementId, data) {
    const ctx = document.getElementById(elementId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Price',
                data: data.values,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {display: false}
            },
            scales: {
                y: {beginAtZero: false}
            }
        }
    });
}

// Form validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', (e) => {
        const required = form.querySelectorAll('[required]');
        let valid = true;
        
        required.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('error');
            } else {
                field.classList.remove('error');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Please fill all required fields');
        }
    });
});

// Progress tracking
function markComplete(contentId) {
    fetch('/api/progress/mark', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({contentId})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-content="${contentId}"]`).classList.add('completed');
            updateProgressBar();
        }
    });
}

function updateProgressBar() {
    const total = document.querySelectorAll('[data-content]').length;
    const completed = document.querySelectorAll('[data-content].completed').length;
    const percent = (completed / total) * 100;
    document.querySelector('.progress-fill').style.width = `${percent}%`;
}
