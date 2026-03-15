const CACHE_NAME = 'pms-v8';
const DYNAMIC_CACHE = 'pms-dynamic-v1';
const ASSETS_TO_CACHE = [
    '/',
    '/dashboard',
    '/sales',
    '/stocks',
    '/items',
    '/offline.html',
    '/manifest.json',
    '/css/app.css',
    '/js/app.js',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/icons/maskable-icon.png',
    '/icons/apple-touch-icon.png',
    '/images/logo.png',
    // Critical CDNs (Precache these immediately)
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css',
    'https://code.jquery.com/jquery-3.6.0.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css',
    'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
    'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
    'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

// Activate Event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME && cache !== DYNAMIC_CACHE) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // 1. Datatable AJAX Interception (Custom logic for offline data display)
    if (url.searchParams.has('draw') && !navigator.onLine) {
        // This looks like a Datatable request (contains 'draw' parameter)
        // We'll let it fail or handle it in the .catch of the navigation/api block
    }

    // 2. API Request Interception (Network First, then Cache)
    if (url.pathname.includes('/api/')) {
        event.respondWith(
            fetch(event.request)
                .then(async (response) => {
                    const cache = await caches.open(DYNAMIC_CACHE);
                    cache.put(event.request, response.clone());
                    return response;
                })
                .catch(() => caches.match(event.request))
        );
        return;
    }

    // 3. Navigation requests (HTML pages) - Network First, then Cache
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .then(async (response) => {
                    const cache = await caches.open(DYNAMIC_CACHE);
                    cache.put(event.request, response.clone());
                    return response;
                })
                .catch(async () => {
                    const cachedResponse = await caches.match(event.request);
                    // Fallback hierarchy: Exact page -> Dashboard -> Offline page
                    return cachedResponse || caches.match('/dashboard') || caches.match('/') || caches.match('/offline.html');
                })
        );
        return;
    }

    // 4. Static Assets & External CDNs (Cache First, then Network)
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) return cachedResponse;

            return fetch(event.request).then(async (networkResponse) => {
                // Dynamic caching for fonts, icons, and CDNs
                if (networkResponse.ok) {
                    const cache = await caches.open(DYNAMIC_CACHE);
                    cache.put(event.request, networkResponse.clone());
                }
                return networkResponse;
            });
        })
    );
});
