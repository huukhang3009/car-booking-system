// sw.js - Service Worker

const CACHE_NAME = 'book-car-cache-v1';
const urlsToCache = [
  '/',
  '/car-booking-system/index.php',
  '/car-booking-system/logo.png',
  '/car-booking-system/logo.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});
