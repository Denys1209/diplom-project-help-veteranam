<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Статус акаунту') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        @if($user->approval_status === App\Enums\ApprovalStatus::WAITING)
                            <div class="mb-6">

                                <h1 class="text-3xl font-bold text-gray-900 mb-2">Ваш акаунт очікує підтвердження</h1>
                                <p class="text-lg text-gray-600">Будь ласка, зачекайте поки адміністратор перевірить і підтвердить ваш акаунт.</p>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            Інформація про процес підтвердження
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Процес підтвердження може тривати від 1 до 3 робочих днів</li>
                                                <li>Адміністратор перевірить надані вами дані</li>
                                                <li>Ви отримаете повідомлення на електронну пошту про статус вашого акаунту</li>
                                                <li>До підтвердження доступ до функцій системи обмежений</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif($user->approval_status === App\Enums\ApprovalStatus::REJECTED)
                            <div class="mb-6">
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">Ваш акаунт було відхилено</h1>
                                <p class="text-lg text-gray-600">На жаль, ваш акаунт не було підтверджено адміністратором.</p>
                            </div>

                            @if($user->rejection_reason)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            Причина відхилення
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>{{ $user->rejection_reason }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">
                                            Що робити далі?
                                        </h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Перевірте правильність наданих даних у вашому профілі</li>
                                                <li>Зв'яжіться з адміністратором для уточнення деталей</li>
                                                <li>Після виправлення проблем можна подати запит на повторну перевірку</li>
                                                <li>Для отримання допомоги напишіть на пошту: support@example.com</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Інформація про ваш акаунт</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Ім'я:</span>
                                        <p class="text-sm text-gray-900">{{ $user->name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Роль:</span>
                                        <p class="text-sm text-gray-900">
                                            @if($user->isVeteran())
                                                Ветеран
                                            @elseif($user->isVolunteer())
                                                Волонтер
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Статус:</span>
                                        <p class="text-sm">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($user->approval_status === App\Enums\ApprovalStatus::WAITING)
                                                     text-grey-800
                                                @elseif($user->approval_status === App\Enums\ApprovalStatus::REJECTED)
                                                     text-red-800
                                                @endif">
                                                {{ $user->approval_status->label() }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-4 justify-center">
                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Редагувати профіль
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Вийти з акаунту
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
