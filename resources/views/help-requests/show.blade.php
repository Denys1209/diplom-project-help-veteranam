<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-600 leading-tight">
                {{ __('Запит допомоги') }}: {{ $helpRequest->title }}
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->isVeteran() && $helpRequest->veteran_id === auth()->id() && $helpRequest->status === 'pending')
                    <x-secondary-button>
                        <a href="{{ route('help-requests.edit', $helpRequest) }}" class="text-gray-700">
                            {{ __('Редагувати') }}
                        </a>
                    </x-secondary-button>

                    <form method="POST" action="{{ route('help-requests.destroy', $helpRequest) }}">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit" onclick="return confirm('{{ __('Ви впевнені, що хочете видалити цей запит?') }}')">
                            {{ __('Видалити') }}
                        </x-danger-button>
                    </form>
                @endif

                @if(auth()->user()->isVolunteer() && $helpRequest->status === 'pending')
                    <form method="POST" action="{{ route('help-requests.volunteer', $helpRequest) }}">
                        @method('PATCH')
                        @csrf
                        <x-primary-button type="submit">
                            {{ __('Взяти запит') }}
                        </x-primary-button>
                    </form>
                @endif

                @if(auth()->user()->isVolunteer() && $helpRequest->volunteer_id === auth()->id() && $helpRequest->status === 'in_progress')
                    <x-primary-button>
                        <a href="{{ route('help-requests.complete-form', $helpRequest) }}" class="text-white">
                            {{ __('Відмітити як виконаний') }}
                        </a>
                    </x-primary-button>
                @endif

                <x-secondary-button>
                    <a href="{{  route('profile.edit')}}" class="text-gray-700">
                        {{ __('Назад') }}
                    </a>
                </x-secondary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Статус запиту -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col items-left space-x-6">
                        <div>
                            <span class="text-gray-500">{{ __('Статус') }}:</span>
                            @if($helpRequest->status == 'pending')
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-600">
                                    {{ __('Очікує') }}
                                </span>
                            @elseif($helpRequest->status == 'in_progress')
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-600">
                                    {{ __('В процесі') }}
                                </span>
                            @elseif($helpRequest->status == 'completed')
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-600">
                                    {{ __('Виконано') }}
                                </span>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $helpRequest->completed_at ? \Carbon\Carbon::parse($helpRequest->completed_at)->format('d.m.Y H:i') : '' }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <span class="text-gray-500">{{ __('Терміновість') }}:</span>
                            @if($helpRequest->urgency == 'high')
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                    {{ __('Висока') }}
                                </span>
                            @elseif($helpRequest->urgency == 'medium')
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-600">
                                    {{ __('Середня') }}
                                </span>
                            @else
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-600">
                                    {{ __('Низька') }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <span class="text-gray-500">{{ __('Створено') }}:</span>
                            <span class="ml-2 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($helpRequest->created_at)->format('d.m.Y H:i') }}
                            </span>
                        </div>

                        @if($helpRequest->deadline)
                            <div>
                                <span class="text-gray-500">{{ __('Дедлайн') }}:</span>
                                <span class="ml-2 text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($helpRequest->deadline)->format('d.m.Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Основна інформація -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Основні деталі -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Деталі запиту') }}</h3>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Категорія') }}</h4>
                                <p class="mt-1">{{ $helpRequest->category->name ?? __('Не вказано') }}</p>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('Опис') }}</h4>
                                <div class="mt-1 prose max-w-none">
                                    {!! nl2br(e($helpRequest->description)) !!}
                                </div>
                            </div>

                            @if($helpRequest->latitude && $helpRequest->longitude)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500">{{ __('Місце розташування') }}</h4>
                                    <div class="mt-2 h-60 bg-gray-100 rounded-lg border border-gray-200">
                                        <!-- Тут можна додати карту, якщо потрібно -->
                                        <div id="map" style="height: 400px;"></div>
                                    </div>
                                    <input type="hidden" id="latitude" value="{{ $helpRequest->latitude }}">
                                    <input type="hidden" id="longitude" value="{{ $helpRequest->longitude }}">
                                    <div class="mt-1 text-sm text-gray-600">
                                        <span>{{ __('Координати') }}: {{ $helpRequest->latitude }}, {{ $helpRequest->longitude }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Фотографії запиту -->
                    @if($helpRequest->photos->where('is_completion_photo', false)->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Фотографії запиту') }}</h3>

                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($helpRequest->photos->where('is_completion_photo', false) as $photo)
                                        <div class="group relative">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $photo->caption }}" class="w-1/2 h-24 object-cover rounded-lg cursor-pointer hover:opacity-90 transition">
                                            @if($photo->caption)
                                                <p class="mt-1 text-xs text-gray-600 truncate">{{ $photo->caption }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Фото виконаного запиту -->
                    @if($helpRequest->status == 'completed' && $helpRequest->photos->where('is_completion_photo', true)->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Фото виконаного запиту') }}</h3>

                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($helpRequest->photos->where('is_completion_photo', true) as $photo)
                                        <div class="group relative">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="{{ $photo->caption }}" class="w-1/2 h-24 object-cover rounded-lg cursor-pointer hover:opacity-90 transition">
                                            @if($photo->caption)
                                                <p class="mt-1 text-xs text-gray-600 truncate">{{ $photo->caption }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500 truncate">
                                                {{ __('Додано') }}: {{ \Carbon\Carbon::parse($photo->created_at)->format('d.m.Y H:i') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Дані ветерана -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Ветеран') }}</h3>

                                <div class="mb-2">
                                    <span class="font-medium">{{ $helpRequest->veteran->name }}</span>
                                </div>

                                <div class="text-sm text-gray-600 mt-2 space-y-1">
                                    @if($helpRequest->veteran->phone)
                                        <div>
                                            <span class="text-gray-500">{{ __('Телефон') }}:</span>
                                            <span>{{ $helpRequest->veteran->phone }}</span>
                                        </div>
                                    @endif

                                    @if($helpRequest->veteran->address)
                                        <div>
                                            <span class="text-gray-500">{{ __('Адреса') }}:</span>
                                            <span>{{ $helpRequest->veteran->address }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($helpRequest->veteran->veteranProfile)
                                    <div class="mt-4 text-sm">
                                        <div class="font-medium text-gray-900 mb-1">{{ __('Військові дані') }}</div>

                                        @if($helpRequest->veteran->veteranProfile->military_unit)
                                            <div class="mt-1">
                                                <span class="text-gray-500">{{ __('Військова частина') }}:</span>
                                                <span>{{ $helpRequest->veteran->veteranProfile->military_unit }}</span>
                                            </div>
                                        @endif

                                        @if($helpRequest->veteran->veteranProfile->service_period)
                                            <div class="mt-1">
                                                <span class="text-gray-500">{{ __('Період служби') }}:</span>
                                                <span>{{ $helpRequest->veteran->veteranProfile->service_period }}</span>
                                            </div>
                                        @endif

                                        @if($helpRequest->veteran->veteranProfile->medical_conditions)
                                            <div class="mt-1">
                                                <span class="text-gray-500">{{ __('Стан здоров\'я') }}:</span>
                                                <span>{{ $helpRequest->veteran->veteranProfile->medical_conditions }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Інформація про волонтера (якщо призначено) -->
                        @if($helpRequest->volunteer)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Волонтер') }}</h3>

                                    <div class="mb-2">
                                        <span class="font-medium">{{ $helpRequest->volunteer->name }}</span>
                                    </div>

                                    @if(auth()->user()->isVeteran() && $helpRequest->veteran_id === auth()->id() || auth()->user()->isAdmin())
                                        <div class="text-sm text-gray-600 mt-2 space-y-1">
                                            @if($helpRequest->volunteer->phone)
                                                <div>
                                                    <span class="text-gray-500">{{ __('Телефон') }}:</span>
                                                    <span>{{ $helpRequest->volunteer->phone }}</span>
                                                </div>
                                            @endif

                                            @if($helpRequest->volunteer->email)
                                                <div>
                                                    <span class="text-gray-500">{{ __('Email') }}:</span>
                                                    <span>{{ $helpRequest->volunteer->email }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-4 text-sm text-gray-600">
                                        <div>
                                            <span class="text-gray-500">{{ __('Призначено') }}:</span>
                                            <span>{{ \Carbon\Carbon::parse($helpRequest->updated_at)->format('d.m.Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Коментарі -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Коментарі') }}</h3>

                            <!-- Форма додавання коментаря -->
                            <form method="POST" action="{{ route('request-comments.store', $helpRequest) }}" class="mb-6">
                                @csrf
                                <div>
                                    <x-text-input id="comment" class="block mt-1 w-full" type="text" name="comment" required autofocus placeholder="{{ __('Додати коментар...') }}" />
                                    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                </div>

                                <div class="mt-2 flex justify-end">
                                    <x-primary-button>
                                        {{ __('Додати коментар') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <!-- Список коментарів -->
                            @if($helpRequest->comments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($helpRequest->comments->sortByDesc('created_at') as $comment)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $comment->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</div>
                                                </div>

                                                @if($comment->user_id === auth()->id())
                                                    <form method="POST" action="{{ route('request-comments.destroy', [$helpRequest, $comment]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-600 hover:text-red-600" onclick="return confirm('{{ __('Ви впевнені, що хочете видалити цей коментар?') }}')">
                                                            {{ __('Видалити') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            <div class="mt-2">
                                                {{ $comment->comment }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500">
                                    {{ __('Поки що немає коментарів') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Бокова панель -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Ви можете додати додаткові елементи бокової панелі тут -->
                </div>

            </div>
        </div>
    </div>
    <!-- Скрипт для карти -->
    <script>
         document.addEventListener('DOMContentLoaded', function () {
            const mapContainer = document.getElementById('map');
            const latitude = document.getElementById('latitude');
            const longitude = document.getElementById('longitude');

            if (mapContainer && latitude && longitude) {
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
                }).addTo(map);
            }
        });
    </script>
</x-app-layout>
