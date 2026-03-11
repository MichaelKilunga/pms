<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public $menu;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (Auth::user()->hasRole('Superadmin')) {
            $role = 'super';
        } elseif (Auth::user()->hasRole('Owner')) {
            $role = 'owner';
        } elseif (Auth::user()->hasRole('Agent')) {
            $role = 'agent';
        } elseif (Auth::user()->hasRole('Manager')) {
            $role = 'admin';
        } elseif (Auth::user()->hasRole('Staff')) {
            $role = 'staff';
        } else {
            $role = 'guest'; // or generic default
        }

        $navigation = config('navigation');
        $menu = $navigation[$role] ?? [];

        // Apply dynamic filters for Staff
        if ($role === 'staff' || $role === 'admin') {
            $pharmacyId = session('current_pharmacy_id');
            $pharmacy = \App\Models\Pharmacy::find($pharmacyId);
            $config = $pharmacy->config ?? [];
            $allowStaffViewStock = $config['allow_staff_view_stock'] ?? false;

            if (!$allowStaffViewStock && !Auth::user()->hasRole('Owner')) {
                foreach ($menu as &$item) {
                    if ($item['label'] === 'Stock' && isset($item['children'])) {
                        $item['children'] = array_filter($item['children'], function($child) {
                            return $child['label'] !== 'Stock';
                        });
                    }
                }
            }
        }

        $this->menu = $menu;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sidebar');
    }
}
