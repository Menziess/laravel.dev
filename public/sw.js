self.addEventListener('install', event => {
  event.waitUntil(
    caches.open('menzies').then(cache => {
      return cache.addAll([
        '/',
        '/sw.js',
        '/js/index.js',
        '/offline.html',
        '/favicon.ico',
      ])
      .then(() => self.skipWaiting());
    })
  )
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      if(event.request.url.endsWith('/public/')) {
        return fetch(event.request);
      } else {
        return response || fetch(event.request);
      }
    }).catch(function() {
      return caches.match('offline.html');
    })
  );
});
