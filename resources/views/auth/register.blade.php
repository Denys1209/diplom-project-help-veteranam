<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div>
            <x-input-label for="name">Ім'я</x-input-label>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email">Електронна пошта</x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone">Номер телефону</x-input-label>
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address">Адреса</x-input-label>
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" autocomplete="address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>
        <!-- Role as Radio Buttons (excluding Admin) -->
        <div class="mt-4">
            <x-input-label for="role">Роль</x-input-label>
            <div class="mt-2 space-y-2">
                @foreach(\App\Enums\UserRole::cases() as $role)
                    @if($role->value !== 'admin')
                        <div class="flex items-center">
                            <input
                                id="role_{{ $role->value }}"
                                name="role"
                                type="radio"
                                value="{{ $role->value }}"
                                {{ old('role') == $role->value ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                            >
                            <label for="role_{{ $role->value }}" class="ml-3 block text-sm font-medium text-gray-700">
                                {{ $role->label() }}
                            </label>
                        </div>
                    @endif
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password">Пароль</x-input-label>
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation">Підтвердити пароль</x-input-label>
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Вже зареєстровані?
            </a>
            <x-primary-button class="ms-4">
                Зареєструватися
            </x-primary-button>
        </div>
    </form>


</x-guest-layout>
