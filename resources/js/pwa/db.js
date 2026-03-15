import Dexie from 'dexie';

export const db = new Dexie('PMS_Database');

// Define Schema
// ++id is auto-incrementing primary key
// sync_status: 0 = synced, 1 = pending
db.version(1).stores({
    items: 'id, name, last_updated',
    stocks: 'id, item_id, pharmacy_id, selling_price, remain_Quantity, expire_date, last_updated',
    sales: '++local_id, id, pharmacy_id, staff_id, item_id, stock_id, quantity, total_price, amount, date, sync_status',
    sync_queue: '++id, action, table, data, timestamp',
    metadata: 'key, value'
});

// Helper to add to sync queue
export async function addToSyncQueue(action, table, data) {
    await db.sync_queue.add({
        action,
        table,
        data,
        timestamp: Date.now()
    });
}
