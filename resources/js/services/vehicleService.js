import axios from 'axios';

const apiUrl = import.meta.env.VITE_API_URL || '/api/v1';

export async function getVehicles() {
  return axios.get(`${apiUrl}/vehicles`);
}

export async function getVehicle(id) {
  return axios.get(`${apiUrl}/vehicles/${id}`);
}

export async function createVehicle(vehicle) {
  return axios.post(`${apiUrl}/vehicles`, vehicle);
}

export async function updateVehicle(id, vehicle) {
  return axios.put(`${apiUrl}/vehicles/${id}`, vehicle);
}

export async function deleteVehicle(id) {
  return axios.delete(`${apiUrl}/vehicles/${id}`);
}

export async function getCurrentLocations() {
  return axios.get(`${apiUrl}/locations/current`);
}

export async function getVehicleHistory(vehicleId) {
  return axios.get(`${apiUrl}/vehicles/${vehicleId}/history`);
}

export async function updateLocation(location) {
  return axios.post(`${apiUrl}/locations`, location);
}

export default {
  getVehicles,
  getVehicle,
  createVehicle,
  updateVehicle,
  deleteVehicle,
  getCurrentLocations,
  getVehicleHistory,
  updateLocation,
};
