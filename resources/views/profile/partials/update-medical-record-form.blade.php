<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Ficha Médica') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Mantenha a sua ficha médica atualizada para um melhor atendimento.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.medical_record.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="blood_type" :value="__('Tipo Sanguíneo')" />
            <x-text-input id="blood_type" name="blood_type" type="text" class="mt-1 block w-full" :value="old('blood_type', $user->medicalRecord->blood_type ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('blood_type')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="height" :value="__('Altura (cm)')" />
                <x-text-input id="height" name="height" type="number" step="0.01" class="mt-1 block w-full" :value="old('height', $user->medicalRecord->height ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('height')" />
            </div>
            <div>
                <x-input-label for="weight" :value="__('Peso (kg)')" />
                <x-text-input id="weight" name="weight" type="number" step="0.01" class="mt-1 block w-full" :value="old('weight', $user->medicalRecord->weight ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('weight')" />
            </div>
        </div>

        <div>
            <x-input-label for="allergies" :value="__('Alergias')" />
            <textarea id="allergies" name="allergies" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('allergies', $user->medicalRecord->allergies ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('allergies')" />
        </div>

        <div>
            <x-input-label for="chronic_conditions" :value="__('Condições Crónicas')" />
            <textarea id="chronic_conditions" name="chronic_conditions" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('chronic_conditions', $user->medicalRecord->chronic_conditions ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('chronic_conditions')" />
        </div>

        <div>
            <x-input-label for="current_medication" :value="__('Medicação Atual')" />
            <textarea id="current_medication" name="current_medication" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('current_medication', $user->medicalRecord->current_medication ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('current_medication')" />
        </div>

        <div>
            <x-input-label for="emergency_contact" :value="__('Contacto de Emergência')" />
            <x-text-input id="emergency_contact" name="emergency_contact" type="text" class="mt-1 block w-full" :value="old('emergency_contact', $user->medicalRecord->emergency_contact ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('emergency_contact')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'medical-record-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
