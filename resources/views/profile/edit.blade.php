<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ваш профіль') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Додаємо кнопку для створення запиту допомоги для ветеранів -->
        @if(auth()->user()->role === \App\Enums\UserRole::VETERAN)
        <div class="flex justify-end">
            <x-primary-button>
                <a href="{{ route('help-requests.create') }}" class="text-white">
                    {{ __('Створити запит допомоги') }}
                </a>
            </x-primary-button>
        </div>
        @endif

        <div class="mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Ваші дані') }}
                        </h2>
                    </div>
                    @php
                        $userProfile = auth()->user();
                    @endphp
                    <div class="space-y-4">
                        <!-- Ім'я -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Ім\'я') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $userProfile->name  }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Електронна пошта') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $userProfile->email  }}</p>
                        </div>

                        <!-- Телефон -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Телефон') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $userProfile->phone  }}</p>
                        </div>

                        <!-- Адреса -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Адреса') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $userProfile->address  }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Include Veteran Profile Form Component if user is a veteran -->

    @if(auth()->user()->role === \App\Enums\UserRole::VETERAN)
        <div class="mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Профіль ветерана') }}
                        </h2>
                        <x-primary-button>
                            <a href="{{ route('veteran-profile.edit') }}" class="text-white">
                                {{ __('Редагувати профіль ветерана') }}
                            </a>
                        </x-primary-button>
                    </div>

                    @php
                        $profile = auth()->user()->veteranProfile ?? new \App\Models\VeteranProfile();
                    @endphp

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Опис потреб') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $profile->needs_description ?: __('Не вказано') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Військова частина') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $profile->military_unit ?: __('Не вказано') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Період служби') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $profile->service_period ?: __('Не вказано') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Стан здоров\'я') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $profile->medical_conditions ?: __('Не вказано') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">{{ __('Видимість профілю') }}</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($profile->is_visible)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-green-100 text-green-800">
                                        {{ __('Видимий для волонтерів') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-gray-100 text-gray-800">
                                        {{ __('Прихований від волонтерів') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Додаємо блок зі списком запитів допомоги від ветерана -->
<div class="mt-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Ваші запити допомоги') }}
                </h2>
                <x-primary-button>
                    <a href="{{ route('help-requests.create') }}" class="text-white">
                        {{ __('Створити новий запит') }}
                    </a>
                </x-primary-button>
            </div>

          <!-- Список запитів допомоги -->
@php
    // Change to paginate instead of get()
    $helpRequests = \App\Models\HelpRequest::where('veteran_id', auth()->id())
                    ->latest()
                    ->paginate(3); // Display 5 items per page, adjust as needed
@endphp

@if($helpRequests->count() > 0)
    <div class="mt-4 flex-col flex">
        @foreach($helpRequests as $request)
            <div class="border rounded-lg p-4 hover:bg-gray-50 mt-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $request->title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($request->description, 100) }}</p>

                        <div class="mt-2 flex flex-col items-left space-x-4 text-sm">
                            <div>
                                <span class="text-gray-500">{{ __('Категорія') }}:</span>
                                <span>{{ $request->category->name ?? __('Не вказано') }}</span>
                            </div>
                            <div class="">
                                <span class="text-gray-500">{{ __('Терміновість') }}:</span>
                                <span>
                                    @if($request->urgency == 'high')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-red-100 text-red-800">
                                            {{ __('Висока') }}
                                        </span>
                                    @elseif($request->urgency == 'medium')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-yellow-100 text-yellow-800">
                                            {{ __('Середня') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-green-100 text-green-800">
                                            {{ __('Низька') }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ __('Статус') }}:</span>
                                <span>
                                    @if($request->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-blue-100 text-blue-800">
                                            {{ __('Очікує') }}
                                        </span>
                                    @elseif($request->status == 'in_progress')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-purple-100 text-purple-800">
                                            {{ __('В процесі') }}
                                        </span>
                                    @elseif($request->status == 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-green-100 text-green-800">
                                            {{ __('Виконано') }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($request->volunteer)
                            <div class="mt-2 text-sm">
                                <span class="text-gray-500">{{ __('Волонтер') }}:</span>
                                <span>{{ $request->volunteer->name }}</span>
                            </div>
                        @endif

                        @if($request->deadline)
                            <div class="mt-1 text-sm">
                                <span class="text-gray-500">{{ __('Дедлайн') }}:</span>
                                <span>{{ \Carbon\Carbon::parse($request->deadline)->format('d.m.Y') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col space-x-2">
                        <a href="{{ route('help-requests.show', $request) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            {{ __('Деталі') }}
                        </a>
                        @if($request->status == 'pending')
                            <a href="{{ route('help-requests.edit', $request) }}" class="text-sm text-gray-600 hover:text-gray-800">
                                {{ __('Редагувати') }}
                            </a>
                            <form method="POST" action="{{ route('help-requests.destroy', $request) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('{{ __('Ви впевнені, що хочете видалити цей запит?') }}')">
                                    {{ __('Видалити') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $helpRequests->links() }}
    </div>
            @else
                <div class="mt-4 p-4 bg-gray-50 rounded-lg text-center">
                    <p class="text-gray-600">{{ __('У вас поки що немає запитів допомоги.') }}</p>
                    <a href="{{ route('help-requests.create') }}" class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-800">
                        {{ __('Створити перший запит') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
    @endif

    <!-- Додаємо блок зі списком запитів допомоги для волонтера -->
    @if(auth()->user()->role === \App\Enums\UserRole::VOLUNTEER)
  <!-- Список запитів допомоги волонтера -->
                @php
                    // Change to paginate instead of get()
                    $volunteerRequests = \App\Models\HelpRequest::where('volunteer_id', auth()->id())
                                        ->where('status', '!=', "completed")
                                        ->latest()
                                        ->paginate(5, ['*'], 'active_page'); // Using custom page name to avoid conflicts

                    $volunteerRequestsCompleted = \App\Models\HelpRequest::where('volunteer_id', auth()->id())
                                                ->where('status', '=', "completed")
                                                ->latest()
                                                ->paginate(5, ['*'], 'completed_page'); // Using custom page name
                @endphp
    <div class="mt-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Ваші активні запити допомоги') }}
                    </h2>
                </div>

                @if($volunteerRequests->count() > 0)
                    <div class="mt-4 flex-col flex">
                        @foreach($volunteerRequests as $request)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 mt-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $request->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($request->description, 100) }}</p>

                                        <div class="mt-2 flex flex-col items-left space-x-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">{{ __('Категорія') }}:</span>
                                                <span>{{ $request->category->name ?? __('Не вказано') }}</span>
                                            </div>
                                            <div class="">
                                                <span class="text-gray-500">{{ __('Терміновість') }}:</span>
                                                <span>
                                                    @if($request->urgency == 'high')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-red-100 text-red-800">
                                                            {{ __('Висока') }}
                                                        </span>
                                                    @elseif($request->urgency == 'medium')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-yellow-100 text-yellow-800">
                                                            {{ __('Середня') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-green-100 text-green-800">
                                                            {{ __('Низька') }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">{{ __('Статус') }}:</span>
                                                <span>
                                                    @if($request->status == 'pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-blue-100 text-blue-800">
                                                            {{ __('Очікує') }}
                                                        </span>
                                                    @elseif($request->status == 'in_progress')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-purple-100 text-purple-800">
                                                            {{ __('В процесі') }}
                                                        </span>
                                                    @elseif($request->status == 'completed')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-green-100 text-green-800">
                                                            {{ __('Виконано') }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-2 text-sm">
                                            <span class="text-gray-500">{{ __('Ветеран') }}:</span>
                                            <span>{{ $request->veteran->name }}</span>
                                        </div>

                                        @if($request->deadline)
                                            <div class="mt-1 text-sm">
                                                <span class="text-gray-500">{{ __('Дедлайн') }}:</span>
                                                <span>{{ \Carbon\Carbon::parse($request->deadline)->format('d.m.Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col space-x-2">
                                        <a href="{{ route('help-requests.show', $request) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                            {{ __('Деталі') }}
                                        </a>
                                        @if($request->status == 'in_progress')
                                                <button type="submit" class="text-sm text-green-600 hover:text-green-800">
                                                      <a href="{{ route('help-requests.complete-form', $request) }}" >
                                                        {{ __('Відмітити як виконаний') }}
                                                      </a>
                                                </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links for Active Requests -->
                    <div class="mt-6">
                        {{ $volunteerRequests->links() }}
                    </div>
                @else
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-gray-600">{{ __('Ви поки що не взяли жодного запиту допомоги.') }}</p>
                        <a href="{{ route('dashboard') }}" class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-800">
                            {{ __('Переглянути доступні запити') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
            <h2 class="text-2xl font-semibold leading-tight text-gray-800 mt-8">{{ __('Виконані запити') }}</h2>
                    @if($volunteerRequestsCompleted->count() > 0)
                        <div class="mt-4 flex-col flex">
                            @foreach($volunteerRequestsCompleted as $request)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 mt-3 opacity-75"> {{-- Added opacity for completed items --}}
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $request->title }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($request->description, 100) }}</p>

                                            <div class="mt-2 flex flex-col items-left space-x-4 text-sm">
                                                <div>
                                                    <span class="text-gray-500">{{ __('Категорія') }}:</span>
                                                    <span>{{ $request->category->name ?? __('Не вказано') }}</span>
                                                </div>
                                                <div class="">
                                                    <span class="text-gray-500">{{ __('Терміновість') }}:</span>
                                                    <span>
                                                        @if($request->urgency == 'high')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-red-100 text-red-800">
                                                                {{ __('Висока') }}
                                                            </span>
                                                        @elseif($request->urgency == 'medium')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-yellow-100 text-yellow-800">
                                                                {{ __('Середня') }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-green-100 text-green-800">
                                                                {{ __('Низька') }}
                                                            </span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">{{ __('Статус') }}:</span>
                                                    <span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-ms font-medium bg-green-100 text-green-800">
                                                            {{ __('Виконано') }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-2 text-sm">
                                                <span class="text-gray-500">{{ __('Ветеран') }}:</span>
                                                <span>{{ $request->veteran->name }}</span>
                                            </div>

                                            @if($request->deadline)
                                                <div class="mt-1 text-sm">
                                                    <span class="text-gray-500">{{ __('Дедлайн') }}:</span>
                                                    <span>{{ \Carbon\Carbon::parse($request->deadline)->format('d.m.Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col space-x-2">
                                            <a href="{{ route('help-requests.show', $request) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                                {{ __('Деталі') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination Links for Completed Requests -->
                        <div class="mt-6">
                            {{ $volunteerRequestsCompleted->links() }}
                        </div>
                    @else
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg text-center">
                            <p class="text-gray-600">{{ __('Ви ще не виконали жодного запиту допомоги.') }}</p>
                        </div>
                    @endif
            </div>
        </div>
    </div>

    @endif

    <div class="p-4 sm:p-8 text-black bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
</div>
</x-app-layout>
