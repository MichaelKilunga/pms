import { db } from './db';
import axios from 'axios';

export const SyncEngine = {
    async sync() {
        if (!navigator.onLine) return;

        console.log('Sync Engine: Starting synchronization...');
        await this.pushPendingOperations();
        await this.pullUpdates();
        console.log('Sync Engine: Synchronization complete.');
    },

    async pushPendingOperations() {
        const queue = await db.sync_queue.toArray();
        if (queue.length === 0) return;

        for (const item of queue) {
            try {
                const response = await axios.post('/api/sync/push', item);
                if (response.data.success) {
                    await db.sync_queue.delete(item.id);
                    // If it was a sale, update the local sale record with the server ID
                    if (item.table === 'sales' && response.data.server_id) {
                        await db.sales.where('local_id').equals(item.data.local_id).modify({
                            id: response.data.server_id,
                            sync_status: 0
                        });
                    }
                }
            } catch (error) {
                console.error('Sync Engine: Failed to push item', item, error);
                // Stop processing if we hit an error (retry later)
                break;
            }
        }
    },

    async pullUpdates() {
        const lastSync = await db.metadata.get('last_pull_timestamp');
        const timestamp = lastSync ? lastSync.value : 0;

        try {
            const response = await axios.get(`/api/sync/pull?since=${timestamp}`);
            const { updates, server_time } = response.data;

            for (const table in updates) {
                if (updates[table].length > 0) {
                    await db[table].bulkPut(updates[table]);
                }
            }

            await db.metadata.put({ key: 'last_pull_timestamp', value: server_time });
        } catch (error) {
            console.error('Sync Engine: Failed to pull updates', error);
        }
    }
};

// Listen for online status
window.addEventListener('online', () => SyncEngine.sync());
