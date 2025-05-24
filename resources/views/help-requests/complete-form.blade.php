<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Відмітити запит як виконаний') }}: {{ $helpRequest->title }}
            </h2>
            <div>
                <x-secondary-button>
                    <a href="{{ route('help-requests.show', $helpRequest) }}" class="text-gray-700">
                        {{ __('Повернутися до запиту') }}
                    </a>
                </x-secondary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('help-requests.complete', $helpRequest) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Відмітити запит як виконаний') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                {{ __('Будь ласка, дайте короткий коментар про виконання запиту та, за можливості, додайте фото виконаної роботи.') }}
                            </p>

                            <!-- Completion comment -->
                            <div class="mb-4">
                                <x-input-label for="completion_comment" :value="__('Коментар до виконання *')" />
                                <x-text-area id="completion_comment"
                                    class="block mt-1 w-full"
                                    name="completion_comment"
                                    rows="4"
                                    required
                                    placeholder="{{ __('Опишіть, як саме ви виконали запит...') }}"
                                >{{ old('completion_comment') }}</x-text-area>
                                <x-input-error :messages="$errors->get('completion_comment')" class="mt-2" />
                            </div>

                            <!-- Completion photo -->
                            <div class="mb-4">
                                <x-input-label for="completion_photo" :value="__('Фото виконаного запиту (необов\'язково)')" />
                                <input
                                    type="file"
                                    id="completion_photo"
                                    name="completion_photo"
                                    accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100"
                                />
                                <x-input-error :messages="$errors->get('completion_photo')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">{{ __('Максимальний розмір: 5MB') }}</p>
                            </div>

                            <!-- Photo caption -->
                            <div class="mb-4">
                                <x-input-label for="photo_caption" :value="__('Підпис до фото (необов\'язково)')" />
                                <x-text-input id="photo_caption" class="block mt-1 w-full" type="text" name="photo_caption" :value="old('photo_caption', 'Фото виконаного запиту')" />
                                <x-input-error :messages="$errors->get('photo_caption')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <x-secondary-button type="button" onclick="window.location='{{ route('help-requests.show', $helpRequest) }}'">
                                    {{ __('Скасувати') }}
                                </x-secondary-button>

                                <x-primary-button class="ml-3">
                                    {{ __('Відмітити як виконаний') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
