<?php

namespace App\Livewire\Profile;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\AccountDeletionRequest;

class RequestDeletionForm extends Component
{
    public $confirmingDeletionRequest = false;
    public $password = '';
    public $reason = '';

    public function confirmDeletionRequest()
    {
        $this->password = '';
        $this->dispatch('confirming-delete-user');
        $this->confirmingDeletionRequest = true;
    }

    public function submitDeletionRequest()
    {
        $this->validate([
            'password' => 'required',
            'reason' => 'required|string|min:10',
        ]);

        if (!Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        AccountDeletionRequest::create([
            'user_id' => Auth::id(),
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        $this->confirmingDeletionRequest = false;
        $this->dispatch('saved');
        $this->dispatch('deletion-request-submitted');
    }

    public function render()
    {
        $pendingRequest = AccountDeletionRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        $rejectedRequest = AccountDeletionRequest::where('user_id', Auth::id())
            ->where('status', 'rejected')
            ->latest()
            ->first();

        return view('livewire.profile.request-deletion-form', [
            'pendingRequest' => $pendingRequest,
            'rejectedRequest' => $rejectedRequest,
        ]);
    }
}
