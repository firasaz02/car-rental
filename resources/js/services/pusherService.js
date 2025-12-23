// Lightweight Pusher / Echo service for the frontend
// Uses window.Echo if present (configured in resources/js/bootstrap.js)

const CHANNEL_NAME = 'vehicle-tracking';
let channel = null;

function ensureChannel() {
  if (typeof window === 'undefined') return null;
  if (window.Echo) {
    if (!channel) channel = window.Echo.channel(CHANNEL_NAME);
    return channel;
  }
  return null;
}

/**
 * Subscribe to location updates.
 * @param {(payload: any) => void} callback
 * @returns {() => void} unsubscribe function
 */
export function subscribeLocationUpdates(callback) {
  const ch = ensureChannel();

  if (ch) {
    const eventName = '.location.updated';
    ch.listen(eventName, callback);

    return () => ch.stopListening(eventName, callback);
  }

  // Fallback: if Echo isn't available, try using Pusher directly (global Pusher)
  if (typeof window !== 'undefined' && window.Pusher) {
    const pusher = new window.Pusher(import.meta.env.VITE_PUSHER_APP_KEY || '', {
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || undefined,
      forceTLS: true,
    });
    const pusherChannel = pusher.subscribe(CHANNEL_NAME);
    const bound = (data) => callback(data);
    pusherChannel.bind('location.updated', bound);

    return () => {
      pusherChannel.unbind('location.updated', bound);
      try { pusher.unsubscribe(CHANNEL_NAME); } catch (e) {}
    };
  }

  // If no realtime client available, return a no-op unsubscribe
  return () => {};
}

export function disconnect() {
  if (channel && window.Echo) {
    try { window.Echo.leave(CHANNEL_NAME); } catch (e) {}
    channel = null;
  }
}

export default {
  subscribeLocationUpdates,
  disconnect,
};
