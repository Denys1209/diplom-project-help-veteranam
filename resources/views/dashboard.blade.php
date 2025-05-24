<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Панель керування') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Map Section - Visible to All Users -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">{{ __('Карта запитів допомоги') }}</h3>
                    <div id="help-requests-map" style="height: 500px; width: 100%;"></div>
                </div>
            </div>

            @if (Auth::user()->isVolunteer())
                <div class="mt-6">
                    {{-- Filter and Sort Form for Volunteers --}}
                    <div class="mb-6 p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800">{{ __('Фільтри та Сортування Запитів') }}</h3>
                        <form method="GET" action="{{ route('dashboard') }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label for="search_filter" class="block text-sm font-medium text-gray-700">{{ __('Пошук') }}</label>
                                    <input type="text" name="search_filter" id="search_filter" value="{{ request('search_filter') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm" placeholder="{{ __('Назва, опис...') }}">
                                </div>
                                <div>
                                    <label for="status_filter" class="block text-sm font-medium text-gray-700">{{ __('Статус') }}</label>
                                    <select name="status_filter" id="status_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                        <option value="">{{ __('Всі статуси') }}</option>
                                        <option value="{{ \App\Enums\HelpRequestStatus::PENDING->value }}" @selected(request('status_filter') == \App\Enums\HelpRequestStatus::PENDING->value)>{{ \App\Enums\HelpRequestStatus::PENDING->label() }}</option>
                                        <option value="{{ \App\Enums\HelpRequestStatus::IN_PROGRESS->value }}" @selected(request('status_filter') == \App\Enums\HelpRequestStatus::IN_PROGRESS->value)>{{ \App\Enums\HelpRequestStatus::IN_PROGRESS->label() }}</option>
                                        <option value="{{ \App\Enums\HelpRequestStatus::COMPLETED->value }}" @selected(request('status_filter') == \App\Enums\HelpRequestStatus::COMPLETED->value)>{{ \App\Enums\HelpRequestStatus::COMPLETED->label() }}</option>
                                        <option value="{{ \App\Enums\HelpRequestStatus::CANCELLED->value }}" @selected(request('status_filter') == \App\Enums\HelpRequestStatus::CANCELLED->value)>{{ \App\Enums\HelpRequestStatus::CANCELLED->label() }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="category_filter" class="block text-sm font-medium text-gray-700">{{ __('Категорія') }}</label>
                                    <select name="category_filter" id="category_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                        <option value="">{{ __('Всі категорії') }}</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->id }}" @selected(request('category_filter') == $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="urgency_filter" class="block text-sm font-medium text-gray-700">{{ __('Терміновість') }}</label>
                                    <select name="urgency_filter" id="urgency_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                        <option value="">{{ __('Будь-яка') }}</option>
                                        <option value="low" @selected(request('urgency_filter') == 'low')>{{ __('Низька') }}</option>
                                        <option value="medium" @selected(request('urgency_filter') == 'medium')>{{ __('Середня') }}</option>
                                        <option value="high" @selected(request('urgency_filter') == 'high')>{{ __('Висока') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="sort_by" class="block text-sm font-medium text-gray-700">{{ __('Сортувати за') }}</label>
                                    <select name="sort_by" id="sort_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                        <option value="created_at" @selected(request('sort_by', 'created_at') == 'created_at')>{{ __('Дата створення') }}</option>
                                        <option value="title" @selected(request('sort_by') == 'title')>{{ __('Назва') }}</option>
                                        <option value="status" @selected(request('sort_by') == 'status')>{{ __('Статус') }}</option>
                                        <option value="urgency" @selected(request('sort_by') == 'urgency')>{{ __('Терміновість') }}</option>
                                        <option value="deadline" @selected(request('sort_by') == 'deadline')>{{ __('Термін виконання') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="sort_direction" class="block text-sm font-medium text-gray-700">{{ __('Напрямок') }}</label>
                                    <select name="sort_direction" id="sort_direction" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm">
                                        <option value="desc" @selected(request('sort_direction', 'desc') == 'desc')>{{ __('За спаданням') }}</option>
                                        <option value="asc" @selected(request('sort_direction') == 'asc')>{{ __('За зростанням') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <button type="submit" class="inline-flex text-black items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs  uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">{{ __('Застосувати') }}</button>
                                <a href="{{ route('dashboard') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">{{ __('Скинути') }}</a>
                            </div>
                        </form>
                    </div>

                    {{-- Help Requests as Cards --}}
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Доступні вам запити') }}</h3>
                    @if(isset($helpRequests) && $helpRequests->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($helpRequests as $helpRequest)
                                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 flex mt-3 flex-col justify-between">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ Str::limit($helpRequest->title, 60) }}</h4>

                                        <p class="text-sm text-gray-600 mb-1">
                                            <strong>{{ __('Категорія') }}:</strong> {{ $helpRequest->category->name ?? __('Н/Д') }}
                                        </p>
                                        <p class="text-sm text-gray-600 mb-1">
                                            <strong>{{ __('Терміновість') }}:</strong>
                                            @if ($helpRequest->urgency === 'low') <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('Низька') }}</span>
                                            @elseif ($helpRequest->urgency === 'medium') <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __('Середня') }}</span>
                                            @elseif ($helpRequest->urgency === 'high') <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('Висока') }}</span>
                                            @else <span class="text-gray-700">{{ __($helpRequest->urgency) }}</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600 mb-3">
                                            <strong>{{ __('Статус') }}:</strong>
                                            @php
                                                $status = \App\Enums\HelpRequestStatus::tryFrom($helpRequest->status);
                                            @endphp
                                            @if ($status === \App\Enums\HelpRequestStatus::PENDING)
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">{{ $status->label() }}</span>
                                            @elseif ($status === \App\Enums\HelpRequestStatus::IN_PROGRESS)
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $status->label() }}</span>
                                                @if($helpRequest->volunteer_id === Auth::id()) <span class="text-xs text-blue-700 ml-1"> ({{ __('Ваш') }})</span> @endif
                                            @elseif ($status === \App\Enums\HelpRequestStatus::COMPLETED)
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $status->label() }}</span>
                                            @elseif ($status === \App\Enums\HelpRequestStatus::CANCELLED)
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ $status->label() }}</span>
                                            @else
                                                <span class="text-gray-700">{{ __($helpRequest->status) }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mb-1" title="{{ $helpRequest->created_at->format('d.m.Y H:i:s') }}">
                                            {{ __('Створено') }}: {{ $helpRequest->created_at->diffForHumans() }}
                                        </p>
                                        @if($helpRequest->deadline)
                                        <p class="text-xs text-gray-500">
                                            {{ __('Термін до') }}: {{ $helpRequest->deadline->format('d.m.Y') }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                        <a href="{{ route('help-requests.show', $helpRequest->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">{{ __('Детальніше') }} &rarr;</a>
                                        @if ($helpRequest->status === \App\Enums\HelpRequestStatus::PENDING->value && is_null($helpRequest->volunteer_id))
                                            <form action="{{ route('help-requests.volunteer', $helpRequest->id) }}" method="POST" class="inline ml-3">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm text-green-600 hover:text-green-900 font-medium" title="{{__('Взяти цей запит в роботу')}}">{{ __('Взяти') }}</button>
                                            </form>
                                        @elseif($helpRequest->status === \App\Enums\HelpRequestStatus::IN_PROGRESS->value && $helpRequest->volunteer_id === Auth::id())
                                             <form action="{{ route('help-requests.complete', $helpRequest->id) }}" method="POST" class="inline ml-3">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm text-purple-600 hover:text-purple-900 font-medium" title="{{__('Відмітити запит як виконаний')}}">{{ __('Завершити') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $helpRequests->links() }} {{-- Pagination links --}}
                        </div>
                    @elseif(isset($helpRequests) && $helpRequests->count() === 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                            <div class="p-6 text-gray-700">
                                {{ __('Наразі немає запитів, що відповідають вашим критеріям.') }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Add Leaflet CSS and JS to the layout -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the map
            var map = L.map('help-requests-map').setView([48.6208, 22.2879], 13); // Uzhhorod coordinates

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add help request markers
            @foreach($allHelpRequests ?? [] as $request)
                @if($request->latitude && $request->longitude)
                    @php
                        // Define marker color based on urgency
                        $iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
                        if ($request->urgency === 'high') {
                            $iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
                        } elseif ($request->urgency === 'medium') {
                            $iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png';
                        }

                        $status = \App\Enums\HelpRequestStatus::tryFrom($request->status);
                        $statusLabel = $status ? $status->label() : __($request->status);
                    @endphp

                    var markerIcon = L.icon({
                        iconUrl: '{{ $iconUrl }}',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });

                    L.marker([{{ $request->latitude }}, {{ $request->longitude }}], {icon: markerIcon})
                        .addTo(map)
                        .bindPopup(`
                        <strong>{{ Str::limit($request->title, 50) }}</strong><br>
                        <span class="text-sm">{{ __('Категорія') }}: {{ $request->category->name ?? __('Н/Д') }}</span><br>
                        <span class="text-sm">{{ __('Статус') }}: {{ $statusLabel }}</span><br>
                        @if($request->status === \App\Enums\HelpRequestStatus::PENDING->value && Auth::user()->isVolunteer() && !$request->volunteer_id)
                            <a href="{{ route('help-requests.show', $request->id) }}" class="text-sm text-indigo-600">{{ __('Детальніше') }}</a>
                            <br><a href="{{ route('help-requests.show', $request->id) }}" class="text-sm text-green-600">{{ __('Взяти запит') }}</a>
                        @endif
                        `);
                @endif
            @endforeach
        });
    </script>
</x-app-layout>
