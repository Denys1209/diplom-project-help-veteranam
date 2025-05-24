<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Створення запиту допомоги') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('help-requests.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Категорія запиту -->
                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Категорія')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">{{ __('Виберіть категорію') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Заголовок запиту -->
                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Заголовок')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Опис запиту -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Опис')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="5" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Терміновість -->
                        <div class="mt-4">
                            <x-input-label for="urgency" :value="__('Терміновість')" />
                            <select id="urgency" name="urgency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>{{ __('Низька') }}</option>
                                <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>{{ __('Середня') }}</option>
                                <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>{{ __('Висока') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('urgency')" class="mt-2" />
                        </div>

                        <!-- Дедлайн -->
                        <div class="mt-4">
                            <x-input-label for="deadline" :value="__('Дедлайн (якщо є)')" />
                            <x-text-input id="deadline" class="block mt-1 w-full" type="date" name="deadline" :value="old('deadline')" />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>


                        <!-- Карта для вибору локації -->
                        <div class="mt-4">
                             <input type="hidden" id="latitude" name="latitude"
                                       value="{{ old('latitude', 48.6208) }}" >
                                <input type="hidden" id="longitude" name="longitude"
                                       value="{{ old('longitude', 22.2879) }}" >
                            <x-input-label :value="__('Виберіть локацію на карті (опціонально)')" />
                            <div id="map" style="height: 400px;"></div>

                            <p class="text-xs text-gray-500 mt-1">{{ __('Клікніть на карту, щоб вибрати місце') }}</p>
                        </div>

                        <!-- Фотографії -->
                        <div class="mt-4">
                            <x-input-label for="photos" :value="__('Фотографії (опціонально)')" />
                            <input id="photos" name="photos[]" type="file" multiple class="block mt-1 w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100"
                            />
                            <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                        </div>

                        <!-- Підпис до фото -->
                        <div class="mt-4">
                            <x-input-label for="photo_caption" :value="__('Підпис до фото (опціонально)')" />
                            <x-text-input id="photo_caption" class="block mt-1 w-full" type="text" name="photo_caption" :value="old('photo_caption')" />
                        </div>

                        <!-- Кнопки -->
                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()">
                                {{ __('Скасувати') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-4">
                                {{ __('Створити запит') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Скрипт для карти -->
    <script>
         document.addEventListener('DOMContentLoaded', function () {
            const latitude = document.getElementById('latitude');
            const longitude = document.getElementById('longitude');

            const map = L.map('map').setView([
                parseFloat(latitude.value) || 48.6208,
                parseFloat(longitude.value) || 22.2879
            ], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add a marker at the initial position
            let marker = L.marker([
                parseFloat(latitude.value) || 48.6208,
                parseFloat(longitude.value) || 22.2879
            ], {
                draggable: true
            }).addTo(map);

            // Update coordinates when marker is dragged
            marker.on('dragend', function (event) {
                const position = marker.getLatLng();
                latitude.value = position.lat;
                longitude.value = position.lng;
            });

            // Allow clicking on the map to reposition the marker
            map.on('click', function (event) {
                marker.setLatLng(event.latlng);
                latitude.value = event.latlng.lat;
                longitude.value = event.latlng.lng;
            });
        });
    </script>
</x-app-layout>
