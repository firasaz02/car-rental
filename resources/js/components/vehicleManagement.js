import {
  getVehicles,
  createVehicle,
  updateVehicle,
  deleteVehicle as deleteVehicleApi
} from '../services/vehicleService';

let vehicles = [];
let editingId = null;

function el(id) { return document.getElementById(id); }

function renderList() {
  const tbody = el('vehicle-table-body');
  tbody.innerHTML = '';
  if (!Array.isArray(vehicles) || vehicles.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="no-vehicles">No vehicles yet</td></tr>';
    return;
  }

  vehicles.forEach(v => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${v.vehicle_number || ''}</td>
      <td>${v.driver_name || ''}</td>
      <td>${v.vehicle_type || ''}</td>
      <td>${v.phone || '-'}</td>
      <td><span class="${v.is_active ? 'status-active' : 'status-inactive'}">${v.is_active ? 'Active' : 'Inactive'}</span></td>
      <td class="actions">
        <button class="btn-icon btn-edit" data-id="${v.id}" title="Edit">✏️</button>
        <button class="btn-icon btn-delete" data-id="${v.id}" title="Delete">🗑️</button>
      </td>
    `;

    tr.querySelector('.btn-edit').addEventListener('click', () => startEdit(v));
    tr.querySelector('.btn-delete').addEventListener('click', () => doDelete(v.id));

    tbody.appendChild(tr);
  });
}

function startEdit(v) {
  editingId = v.id;
  const form = el('vehicle-form');
  form.vehicle_number.value = v.vehicle_number || '';
  form.driver_name.value = v.driver_name || '';
  form.vehicle_type.value = v.vehicle_type || '';
  form.phone.value = v.phone || '';
  el('vehicle-form-wrapper').style.display = 'block';
  el('form-title').textContent = 'Edit Vehicle';
}

function resetForm() {
  editingId = null;
  const form = el('vehicle-form');
  form.reset();
  // ensure checkbox defaults to checked
  const chk = form.querySelector('input[name="is_active"]');
  if (chk) chk.checked = true;
  el('form-title') && (el('form-title').textContent = 'Add New Vehicle');
}

async function load() {
  try {
    const res = await getVehicles();
    if (res && res.data && res.data.success) {
      vehicles = res.data.data;
      renderList();
    } else {
      vehicles = [];
      renderList();
    }
  } catch (e) {
    console.error('Failed to load vehicles', e);
    vehicles = [];
    renderList();
  }
}

async function doCreate(formData) {
  try {
    const res = await createVehicle(formData);
    if (res && res.data && res.data.success) {
      await load();
      alert('Vehicle created');
      resetForm();
      el('vehicle-form-container').style.display = 'none';
    } else {
      alert('Create failed');
    }
  } catch (e) {
    console.error('Create error', e);
    alert('Create error');
  }
}

async function doUpdate(id, formData) {
  try {
    const res = await updateVehicle(id, formData);
    if (res && res.data && res.data.success) {
      await load();
      alert('Vehicle updated');
      resetForm();
      el('vehicle-form-container').style.display = 'none';
    } else {
      alert('Update failed');
    }
  } catch (e) {
    console.error('Update error', e);
    alert('Update error');
  }
}

async function doDelete(id) {
  if (!confirm('Delete this vehicle?')) return;
  try {
    const res = await deleteVehicleApi(id);
    if (res && res.data && res.data.success) {
      await load();
      alert('Vehicle deleted');
    } else {
      alert('Delete failed');
    }
  } catch (e) {
    console.error('Delete error', e);
    alert('Delete error');
  }
}

function bindForm() {
  const toggle = el('toggle-form');
  const container = el('vehicle-form-wrapper');
  const form = el('vehicle-form');
  const resetBtn = el('reset-form');

  toggle && toggle.addEventListener('click', () => {
    container.style.display = container.style.display === 'block' ? 'none' : 'block';
    if (container.style.display === 'none') resetForm();
  });

  resetBtn && resetBtn.addEventListener('click', () => { container.style.display = 'none'; resetForm(); });

  form && form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
      vehicle_number: form.querySelector('input[name="vehicle_number"]').value,
      driver_name: form.querySelector('input[name="driver_name"]').value,
      vehicle_type: form.querySelector('select[name="vehicle_type"]').value,
      phone: form.querySelector('input[name="phone"]').value,
      is_active: form.querySelector('input[name="is_active"]').checked
    };

    if (editingId) {
      await doUpdate(editingId, data);
    } else {
      await doCreate(data);
    }
  });
}

export function initVehicleManagement() {
  bindForm();
  load();

  // optionally initialize the vehicle map if available
  if (window.initVehicleMap) {
    window.initVehicleMap({ lat: 36.8065, lng: 10.1815, zoom: 6 });
  }
}

// expose for blade loader
window.initVehicleManagement = initVehicleManagement;

export default initVehicleManagement;
