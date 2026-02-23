<x-action-section>
    <x-slot name="title">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>

        @if ($pendingRequest)
            <div class="mt-5 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                <p class="font-bold">{{ __('Request Pending') }}</p>
                <p>{{ __('You have a pending account deletion request submitted on :date.', ['date' => $pendingRequest->created_at->format('M d, Y')]) }}</p>
                <p class="mt-2"><strong>{{ __('Reason:') }}</strong> {{ $pendingRequest->reason }}</p>
            </div>
        @else
            @if ($rejectedRequest)
                <div class="mt-5 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    <p class="font-bold">{{ __('Request Rejected') }}</p>
                    <p>{{ __('Your previous account deletion request was rejected on :date.', ['date' => $rejectedRequest->reviewed_at->format('M d, Y')]) }}</p>
                    <p class="mt-2"><strong>{{ __('Rejection Reason:') }}</strong> {{ $rejectedRequest->rejection_reason }}</p>
                    <p class="mt-2">{{ __('You may submit a new request below if needed.') }}</p>
                </div>
            @endif

            <div class="mt-5">
                <x-danger-button wire:click="confirmDeletionRequest" wire:loading.attr="disabled">
                    {{ __('Request Account Deletion') }}
                </x-danger-button>
            </div>
        @endif

        <!-- Request Deletion Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingDeletionRequest">
            <x-slot name="title">
                {{ __('Request Account Deletion') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete your account? This will send a request to the system administrator for approval. Please provide a reason for your request and enter your password to confirm.') }}

                <div class="mt-4">
                    <x-label for="reason" value="{{ __('Reason for Deletion') }}" />
                    <x-textarea id="reason" class="mt-1 block w-full" wire:model="reason" placeholder="{{ __('Please explain why you want to delete your account...') }}" />
                    <x-input-error for="reason" class="mt-2" />
                </div>

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input type="password" class="mt-1 block w-3/4"
                                id="password"
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="submitDeletionRequest" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingDeletionRequest')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="submitDeletionRequest" wire:loading.attr="disabled">
                    {{ __('Submit Request') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
