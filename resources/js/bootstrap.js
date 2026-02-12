import axios from 'axios';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'local',
    wsHost: '127.0.0.1',
    wsPort: 8080,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
});

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
