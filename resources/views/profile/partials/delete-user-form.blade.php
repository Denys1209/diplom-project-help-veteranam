<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Видалити обліковий запис
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Після видалення облікового запису всі ресурси та дані будуть назавжди видалені. Перед видаленням облікового запису завантажте будь-які дані або інформацію, яку ви хочете зберегти.
        </p>
    </header>
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        Видалити обліковий запис
    </x-danger-button>
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
            <h2 class="text-lg font-medium text-gray-900">
                Ви впевнені, що хочете видалити свій обліковий запис?
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Будь ласка, введіть пароль для підтвердження бажання назавжди видалити ваш обліковий запис.
            </p>
            <div class="mt-6">
                <x-input-label for="password" value="Пароль" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Пароль"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Скасувати
                </x-secondary-button>
                <x-danger-button class="ms-3">
                    Видалити обліковий запис
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
