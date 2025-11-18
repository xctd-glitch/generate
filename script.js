/**
 * Placeholder dashboard script.
 * Previously, scripts were embedded directly in the PHP template. This file
 * exists to avoid missing-asset errors and provide a single location for
 * future behaviour. Replace or extend the stub below with real logic.
 */

document.addEventListener('DOMContentLoaded', () => {
    const notice = document.getElementById('dashboard-notice');
    if (notice) {
        notice.insertAdjacentHTML(
            'beforeend',
            '<br><small class="text-muted">Tambahkan fitur dashboard di sini.</small>'
        );
    }
    console.info('Dashboard placeholder script loaded.');
});
