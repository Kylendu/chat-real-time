<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $photo;
    public $currentPhoto;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->currentPhoto = Auth::user()->profile_photo;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'photo' => ['nullable', 'image', 'max:1024'],
        ]);

        $photoUpdated = false;
        if ($this->photo) {
            $photoPath = $this->photo->store('profile-photos', 'public');
            $user->profile_photo = $photoPath;
            $photoUpdated = true;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        $this->currentPhoto = $user->profile_photo;
        $this->photo = null;

        // Refresh the page if photo was updated to update the header
        if ($photoUpdated) {
            $this->dispatch('refresh-page');
        }
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <!-- Profile Photo -->
        <div>
            <x-input-label for="photo" :value="__('Profile Photo')" />

            <!-- Current Profile Photo -->
            <div class="mt-2">
                @if ($photo)
                    <!-- Show temporary image preview when a new photo is selected -->
                    <img src="{{ $photo->temporaryUrl() }}" alt="{{ __('Profile Photo Preview') }}"
                        class="rounded-full h-20 w-20 object-cover">
                @elseif ($currentPhoto)
                    <img src="{{ asset('storage/' . $currentPhoto) }}" alt="{{ __('Profile Photo') }}"
                        class="rounded-full h-20 w-20 object-cover">
                @else
                    <div class="rounded-full h-20 w-20 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">{{ substr($name, 0, 1) }}</span>
                    </div>
                @endif
            </div>

            <!-- New Profile Photo -->
            <div class="mt-2">
                <x-text-input wire:model.live="photo" id="photo" name="photo" type="file"
                    class="mt-1 block w-full" accept="image/*" />
                <div wire:loading wire:target="photo" class="mt-2 text-sm text-gray-600">
                    {{ __('Uploading...') }}
                </div>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('refresh-page', () => {
                window.location.reload();
            });
        });
    </script>
</section>
