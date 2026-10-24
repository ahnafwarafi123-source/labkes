// KESMAS PWA Service Worker
const CACHE_NAME = 'kesmas-v1.2';
const urlsToCache = [
  '/',
  '/assets/css/ui-polish.css',
  '/assets/img/logo.png',
  '/assets/img/background.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => response || fetch(event.request))
  );
});

