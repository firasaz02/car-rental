import './bootstrap';
import './components/vehicleManagement';

// Simple map initialization without Google Maps
window.initVehicleMap = async function (options = {}) {
	console.log('Map initialized without Google Maps dependency');
	
	// Return a simple mock map instance
	return {
		map: null,
		loadVehicles: async () => {
			console.log('Mock map: loadVehicles called');
		},
		updateVehicleLocation: () => {
			console.log('Mock map: updateVehicleLocation called');
		},
		destroy: () => {
			console.log('Mock map: destroy called');
		}
	};
};
