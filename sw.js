// Service Worker - Unregister old versions
self.addEventListener('install', function(event) {
    // Skip waiting to activate immediately
    self.skipWaiting();
});

self.addEventListener('activate', function(event) {
    // Clear all caches
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    return caches.delete(cacheName);
                })
            );
        }).then(function() {
            // Unregister this service worker
            return self.registration.unregister();
        })
    );
});

// Don't handle any fetch requests
self.addEventListener('fetch', function(event) {
    // Let the browser handle all requests normally
    return;
});
