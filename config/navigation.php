<?php

return [
    'staff' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'bi bi-grid-fill',
        ],
        [
            'label' => 'Sales',
            'icon' => 'bi bi-currency-dollar',
            'children' => [
                ['label' => 'Sell medicine', 'route' => 'sales'],
                ['label' => 'Sales Returns', 'route' => 'salesReturns'],
                ['label' => 'Document Sales', 'route' => 'salesNotes'],
            ],
        ],
        [
            'label' => 'Stock',
            'icon' => 'bi bi-box-seam',
            'children' => [
                ['label' => 'Stock Balance', 'route' => 'stocks.balance'],
            ],
        ],
        [
            'label' => 'Notifications',
            'icon' => 'bi bi-bell',
            'children' => [
                ['label' => 'All Notifications', 'route' => 'notifications'],
                ['label' => 'Messages', 'route' => 'agent.messages', 'params' => ['action' => 'index']],
            ],
        ],
        [
            'label' => 'Expenses',
            'icon' => 'bi bi-wallet2',
            'children' => [
                ['label' => 'Expenses', 'route' => 'expenses.index'],
            ],
        ],
    ],

    'owner' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'bi bi-grid-fill',
        ],
        [
            'label' => 'Sales',
            'icon' => 'bi bi-currency-dollar',
            'children' => [
                ['label' => 'Sell medicine', 'route' => 'sales'],
                ['label' => 'Sales Returns', 'route' => 'salesReturns'],
                ['label' => 'Documented Sales', 'route' => 'salesNotes'],
            ],
        ],
        [
            'label' => 'Stock',
            'icon' => 'bi bi-box-seam',
            'children' => [
                ['label' => 'All medicine', 'route' => 'medicines'],
                ['label' => 'Stock', 'route' => 'stock'],
                ['label' => 'Stock Balance', 'route' => 'stocks.balance'],
                ['label' => 'Stock Transfers', 'route' => 'stockTransfers'],
            ],
        ],
        [
            'label' => 'Admin',
            'icon' => 'bi bi-shield-lock',
            'children' => [
                ['label' => 'Pharmacist', 'route' => 'staff'],
                ['label' => 'Category', 'route' => 'category'],
                ['label' => 'Pharmacies', 'route' => 'pharmacies'],
                ['label' => 'Contracts', 'route' => 'myContracts'],
            ],
        ],

        [
            'label' => 'Expenses',
            'icon' => 'bi bi-wallet2',
            'children' => [
                ['label' => 'Categories', 'route' => 'expenses.category'],
                ['label' => 'Vendors', 'route' => 'expenses.vendors'],
                ['label' => 'Expenses', 'route' => 'expenses.index'],
            ],
        ],
        [
            'label' => 'Debts',
            'icon' => 'bi bi-credit-card',
            'children' => [
                ['label' => 'All Debts', 'route' => 'debts.index'],
                ['label' => 'All Installments', 'route' => 'installments.installment'],
            ],
        ],
        [
            'label' => 'Reports',
            'icon' => 'bi bi-bar-chart',
            'children' => [
                ['label' => 'Reports', 'route' => 'reports.all'],
                ['label' => 'Notifications', 'route' => 'notifications'],
                ['label' => 'Messages', 'route' => 'agent.messages', 'params' => ['action' => 'index']],
            ],
        ],
    ],

    'admin' => [
        // Admin shares the same menu structure as Owner in the original code
        // Reuse 'owner' config or define explicitly if they diverge later.
        // For now, mirroring owner structure as per original blade logic:
        // @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'bi bi-grid-fill',
        ],
        [
            'label' => 'Sales',
            'icon' => 'bi bi-currency-dollar',
            'children' => [
                ['label' => 'Sell medicine', 'route' => 'sales'],
                ['label' => 'Sales Returns', 'route' => 'salesReturns'],
                ['label' => 'Documented Sales', 'route' => 'salesNotes'],
            ],
        ],
        [
            'label' => 'Stock',
            'icon' => 'bi bi-box-seam',
            'children' => [
                ['label' => 'All medicine', 'route' => 'medicines'],
                ['label' => 'Stock', 'route' => 'stock'],
                ['label' => 'Stock Balance', 'route' => 'stocks.balance'],
                ['label' => 'Stock Transfers', 'route' => 'stockTransfers'],
            ],
        ],
        [
            'label' => 'Admin',
            'icon' => 'bi bi-shield-lock',
            'children' => [
                ['label' => 'Pharmacist', 'route' => 'staff'],
                ['label' => 'Category', 'route' => 'category'],
                ['label' => 'Pharmacies', 'route' => 'pharmacies'],
                ['label' => 'Contracts', 'route' => 'myContracts'],
            ],
        ],
        [
            'label' => 'Expenses',
            'icon' => 'bi bi-wallet2',
            'children' => [
                ['label' => 'Categories', 'route' => 'expenses.category'],
                ['label' => 'Vendors', 'route' => 'expenses.vendors'],
                ['label' => 'Expenses', 'route' => 'expenses.index'],
            ],
        ],
        [
            'label' => 'Debts',
            'icon' => 'bi bi-credit-card',
            'children' => [
                ['label' => 'All Debts', 'route' => 'debts.index'],
                ['label' => 'All Installments', 'route' => 'installments.installment'],
            ],
        ],
        [
            'label' => 'Reports',
            'icon' => 'bi bi-bar-chart',
            'children' => [
                ['label' => 'Reports', 'route' => 'reports.all'],
                ['label' => 'Notifications', 'route' => 'notifications'],
                ['label' => 'Messages', 'route' => 'agent.messages', 'params' => ['action' => 'index']],
            ],
        ],
    ],

    'super' => [
        [
            'label' => 'System',
            'icon' => 'bi bi-motherboard',
            'children' => [
                ['label' => 'Dashboard', 'route' => 'dashboard'],
                ['label' => 'System Users', 'route' => 'superadmin.users'],
                ['label' => 'Pharmacies', 'route' => 'superadmin.pharmacies'],
                ['label' => 'Packages', 'route' => 'packages'],
                ['label' => 'All medicines', 'route' => 'allMedicines.all'],
                ['label' => 'Notifications', 'route' => 'notifications'],
                ['label' => 'Contracts', 'route' => 'contracts'],
                ['label' => "Agent's Contracts", 'route' => 'agent.packages', 'params' => ['action' => 'index']],
                ['label' => 'Schedules', 'route' => 'update.contracts'],
                ['label' => 'Pharmacies (Agent)', 'route' => 'agent.pharmacies', 'params' => ['action' => 'index']],
                ['label' => 'Messages', 'route' => 'agent.messages', 'params' => ['action' => 'index']],
                ['label' => 'Agents Registration', 'route' => 'agent.completeRegistration', 'params' => ['action' => 'index']],
                ['label' => 'Activities', 'route' => 'audits.index'],
                ['label' => 'Global Notifications', 'route' => 'superAdmin.notifications.index'],
            ],
        ],
    ],

    'agent' => [
        [
            'label' => 'Agent',
            'icon' => 'bi bi-person-badge',
            'children' => [
                ['label' => 'Dashboard', 'route' => 'dashboard'],
                ['label' => 'Pharmacies', 'route' => 'agent.pharmacies', 'params' => ['action' => 'index']],
                ['label' => 'Packages', 'route' => 'agent.packages', 'params' => ['action' => 'index']],
                ['label' => 'Messages', 'route' => 'agent.messages', 'params' => ['action' => 'index']],
            ],
        ],
    ],
];
