var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/css/app.css',
    '/js/app.js',
    "/storage/01JSYXZ22JCX5JHXHMH05SAATP.png",
    "/storage/01JSYXZ22M2VG2Q4TJSRHSQJD0.png",
    "/storage/01JSYXZ22PTPSDGQ0SQQY6DNTP.png",
    "/storage/01JSYXZ22RXCMAZHD9CB7QSRMT.png",
    "/storage/01JSYXZ22TRCSM4VX76B62ZW0G.png",
    "/storage/01JSYXZ22WCCJKPKM7SBFCR899.png",
    "/storage/01JSYXZ22YGZFF3K50N8K9SFFY.png",
    "/storage/01JSYXZ23065KX65F87TVY1X2H.png"
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});
