<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Профіль ветерана') }}
</h2>
</x-slot>
<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 text-gray-900">
<form method="POST" action="{{ route('veteran-profile.update') }}">
@csrf
@method('PUT')
<!-- Потребності -->
<div class="mb-4">
<x-input-label for="needs_description" :value="__('Опис потреб')" />
<x-textarea id="needs_description" name="needs_description" rows="4" class="block mt-1 w-full"
:value="old('needs_description', $profile->needs_description)"></x-textarea>
<x-input-error :messages="$errors->get('needs_description')" class="mt-2" />
</div>
<!-- Військова частина -->
<div class="mb-4">
<x-input-label for="military_unit" :value="__('Військова частина')" />
<x-text-input id="military_unit" class="block mt-1 w-full" type="text" name="military_unit"
:value="old('military_unit', $profile->military_unit)" />
<x-input-error :messages="$errors->get('military_unit')" class="mt-2" />
</div>
<!-- Період служби -->
<div class="mb-4">
<x-input-label for="service_period" :value="__('Період служби')" />
<x-text-input id="service_period" class="block mt-1 w-full" type="text" name="service_period"
:value="old('service_period', $profile->service_period)" />
<x-input-error :messages="$errors->get('service_period')" class="mt-2" />
</div>
<!-- Стан здоров'я -->
<div class="mb-4">
<x-input-label for="medical_conditions" :value="__('Стан здоров\'я')" />
<x-textarea id="medical_conditions" name="medical_conditions" rows="3" class="block mt-1 w-full"
:value="old('medical_conditions', $profile->medical_conditions)"></x-textarea>
<x-input-error :messages="$errors->get('medical_conditions')" class="mt-2" />
</div>
<!-- Видимість -->
<div class="block mt-4">
<label for="is_visible" class="inline-flex items-center">
<input id="is_visible" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
name="is_visible" value="1" {{ old('is_visible', $profile->is_visible) ? 'checked' : '' }}>
<span class="ml-2 text-sm text-gray-600">{{ __('Зробити профіль видимим для волонтерів') }}</span>
</label>
</div>
<div class="flex items-center justify-end mt-4">
<x-primary-button class="ml-4">
{{ __('Оновити профіль') }}
</x-primary-button>
</div>
</form>
</div>
</div>
</div>
</div>
</x-app-layout>
