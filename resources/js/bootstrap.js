import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Laravel Echo + Pusher setup
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
	broadcaster: 'pusher',
	key: import.meta.env.VITE_PUSHER_APP_KEY || process.env.MIX_PUSHER_APP_KEY || 'your-pusher-key',
	cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_APP_CLUSTER || 'mt1',
	forceTLS: true,
	encrypted: true,
	wsHost: import.meta.env.VITE_PUSHER_HOST || undefined,
	wsPort: import.meta.env.VITE_PUSHER_PORT || undefined,
	enabledTransports: ['ws', 'wss'],
});
