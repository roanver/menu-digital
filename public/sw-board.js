/**
 * Service Worker — Board (cartelería digital)
 * Cachea la pantalla del menú para que siga mostrando aunque se corte internet.
 */
const CACHE = 'board-v1';

self.addEventListener('install', () => self.skipWaiting());

self.addEventListener('activate', event => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);

    // Solo interceptar GET del mismo origen
    if (event.request.method !== 'GET') return;
    if (url.origin !== self.location.origin) return;

    // Nunca cachear el endpoint de versión
    if (url.pathname.endsWith('/version')) return;

    // Solo rutas del board
    if (!url.pathname.startsWith('/board/')) return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                if (response.ok) {
                    const copy = response.clone();
                    caches.open(CACHE).then(cache => cache.put(event.request, copy));
                }
                return response;
            })
            .catch(() =>
                caches.match(event.request).then(cached =>
                    cached || new Response(
                        '<!DOCTYPE html><html><body style="background:#0f1012;color:#fff;display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;font-size:2vw">Sin conexión — reconectando…</body></html>',
                        { headers: { 'Content-Type': 'text/html' } }
                    )
                )
            )
    );
});
