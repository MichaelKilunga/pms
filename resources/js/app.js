import './bootstrap';
import { db, addToSyncQueue } from './pwa/db';
import { SyncEngine } from './pwa/sync-engine';

// Expose to window for use in blade templates
window.db = db;
window.addToSyncQueue = addToSyncQueue;
window.SyncEngine = SyncEngine;

// Run sync on load if online
if (navigator.onLine) {
    SyncEngine.sync();
}
