// Configuration
const API_BASE = 'http://localhost:8000/api/vignettes';

// Alert
function showAlert(message, type = 'success') {
    const alert = document.getElementById('alert');
    alert.textContent = message;
    alert.className = `alert ${type}`;
    setTimeout(() => {
        alert.className = 'alert';
    }, 4000);
}
// Load all vignettes
async function loadVignettes() {
    try {
        const res = await fetch(API_BASE);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        
        const data = await res.json();
        const tbody = document.getElementById('vignettes-table').querySelector('tbody');
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No vignettes found</td></tr>';
        }
        
        // Clear existing rows
        tbody.innerHTML = '';

        // Load new rows
        data.forEach(v => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${v.id}</td>
                <td>${v.vehicle_id}</td>
                <td>${v.type}</td>
                <td>${v.category}</td>
                <td>${v.region || '-'}</td>
                <td>${v.year}</td>
                <td>${formatDate(v.valid_from)}</td>
                <td>${formatDate(v.valid_to)}</td>
                <td>
                    <div class="d-flex gap-1">
                        <button data-id="${v.id}" class="btn btn-sm btn-info edit text-white">Edit</button>
                        <button data-id="${v.id}" class="btn btn-sm btn-danger delete">Delete</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (err) {
        console.error('Error loading vignettes:', err);
        showAlert('Failed to load vignettes', 'error');
    }
}
// Format date
function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US');
}
// Clear form
function clearForm() {
    
    document.getElementById('vignette-id').value = '';
    ['vehicle_id', 'type', 'category', 'region', 'year', 'valid_from', 'valid_to'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('save-btn').innerText = 'Create';
}
// Create vignette
async function createVignette(payload) {
    const res = await fetch(API_BASE, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    if (!res.ok) {
        const err = await res.json();
        throw new Error(err.message || 'Failed to create vignette');
    }
    return res.json();
}
// Update vignette
async function updateVignette(id, payload) {
    const res = await fetch(`${API_BASE}/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    if (!res.ok) {
        const err = await res.json();
        throw new Error(err.message || 'Failed to update vignette');
    }
    return res.json();
}
// Delete vignette
async function deleteVignette(id) {
    const res = await fetch(`${API_BASE}/${id}`, { method: 'DELETE' });
    if (!res.ok) {
        throw new Error('Failed to delete vignette');
    }
}
// Form submission
document.getElementById('vignette-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('vignette-id').value;
    const payload = {
        vehicle_id: parseInt(document.getElementById('vehicle_id').value, 10),
        type: document.getElementById('type').value,
        category: document.getElementById('category').value,
        region: document.getElementById('region').value || null,
        year: parseInt(document.getElementById('year').value, 10),
        valid_from: document.getElementById('valid_from').value,
        valid_to: document.getElementById('valid_to').value,
    };
    try {
        if (id) {
            await updateVignette(id, payload);
            showAlert(`Vignette id: ${id} updated successfully`);
        } else {
            const result = await createVignette(payload);
            showAlert(`Vignette id: ${result.id} created successfully`);
        }
        await loadVignettes();
        clearForm();
    } catch (err) {
        console.error('Error:', err);
        showAlert(err.message || 'Error saving vignette', 'error');
    }
});

// Clear button
document.getElementById('cancel-btn').addEventListener('click', clearForm);

// Edit and delete button
document.querySelector('#vignettes-table tbody').addEventListener('click', async (e) => {
    const id = e.target?.dataset?.id;
    if (!id) return;
    if (e.target.classList.contains('delete')) {
        if (confirm('Are you sure you want to delete this vignette?')) {
            try {
                await deleteVignette(id);
                showAlert('Vignette deleted successfully');
                await loadVignettes();
            } catch (err) {
                console.error('Error:', err);
                showAlert('Failed to delete vignette', 'error');
            }
        }
    }
    if (e.target.classList.contains('edit')) {
        try {
            const res = await fetch(`${API_BASE}/${id}`);
            if (!res.ok) throw new Error('Failed to fetch vignette');
            
            const v = await res.json();
            document.getElementById('vignette-id').value = v.id;
            document.getElementById('vehicle_id').value = v.vehicle_id;
            document.getElementById('type').value = v.type;
            document.getElementById('category').value = v.category;
            document.getElementById('region').value = v.region || '';
            document.getElementById('year').value = v.year;
            document.getElementById('valid_from').value = v.valid_from ? v.valid_from.split('T')[0] : '';
            document.getElementById('valid_to').value = v.valid_to ? v.valid_to.split('T')[0] : '';
            document.getElementById('save-btn').innerText = 'Update';
            
            // Scroll to form
            document.getElementById('vignette-form').scrollIntoView({ behavior: 'smooth' });
            showAlert(`Editing vignette id: ${id}`);
        } catch (err) {
            console.error('Error:', err);
            showAlert('Failed to load vignette', 'error');
        }
    }
});
// Initial load
loadVignettes();