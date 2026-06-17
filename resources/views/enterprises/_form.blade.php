@php
    $selectClass = 'mt-2 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 disabled:cursor-not-allowed disabled:bg-zinc-100 disabled:text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:focus:border-sky-400 dark:focus:ring-sky-400/30';
@endphp

<form
    action="{{ $action }}"
    method="POST"
    class="grid gap-6"
    x-data="{
        countryId: String(@js($selectedCountryId ? (string) $selectedCountryId : '')),
        stateId: String(@js($selectedStateId ? (string) $selectedStateId : '')),
        cityId: String(@js($selectedCityId ? (string) $selectedCityId : '')),
        states: @js($states),
        cities: @js($cities),
        get filteredStates() {
            return this.states
                .filter((state) => String(state.countries_Id) === this.countryId)
                .sort((a, b) => a.name.localeCompare(b.name));
        },
        get filteredCities() {
            return this.cities
                .filter((city) => String(city.states_Id) === this.stateId)
                .sort((a, b) => {
                    if (a.is_capital !== b.is_capital) {
                        return a.is_capital ? -1 : 1;
                    }

                    return a.name.localeCompare(b.name);
                });
        },
        changeCountry() {
            this.stateId = '';
            this.cityId = '';
        },
        changeState() {
            this.cityId = '';
        },
    }"
>
    @csrf

    @isset($method)
        @method($method)
    @endisset

    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <label for="document_types_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Tipo de documento
            </label>
            <select id="document_types_Id" name="document_types_Id" required class="{{ $selectClass }}">
                <option value="">Seleccione un tipo de documento</option>
                @foreach ($documentTypes as $documentType)
                    <option value="{{ $documentType->getKey() }}" @selected(old('document_types_Id', $enterprise->document_types_Id ?? '') == $documentType->getKey())>
                        {{ $documentType->name }}
                    </option>
                @endforeach
            </select>
            @error('document_types_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <flux:input
            name="document_number"
            label="Número de documento"
            :value="old('document_number', $enterprise->document_number ?? '')"
            required
            maxlength="50"
            autofocus
        />

        <flux:input
            name="legal_name"
            label="Razón social"
            :value="old('legal_name', $enterprise->legal_name ?? '')"
            required
            maxlength="180"
        />

        <flux:input
            name="trade_name"
            label="Nombre comercial"
            :value="old('trade_name', $enterprise->trade_name ?? '')"
            maxlength="180"
        />

        <flux:input
            name="email"
            label="Correo"
            type="email"
            :value="old('email', $enterprise->email ?? '')"
            maxlength="180"
        />

        <flux:input
            name="phone"
            label="Teléfono"
            :value="old('phone', $enterprise->phone ?? '')"
            maxlength="40"
        />

        <div>
            <label for="countries_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                País
            </label>
            <select id="countries_Id" name="countries_Id" x-model="countryId" x-on:change="changeCountry" required class="{{ $selectClass }}">
                <option value="">Seleccione un país</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->getKey() }}">{{ $country->name }}</option>
                @endforeach
            </select>
            @error('countries_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="states_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Departamento/Estado
            </label>
            <select id="states_Id" name="states_Id" x-model="stateId" x-on:change="changeState" :disabled="!countryId" class="{{ $selectClass }}">
                <option value="">Seleccione un departamento/estado</option>
                <template x-for="state in filteredStates" :key="state.Id">
                    <option :value="String(state.Id)" x-text="state.name"></option>
                </template>
            </select>
            @error('states_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="cities_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Ciudad
            </label>
            <select id="cities_Id" name="cities_Id" x-model="cityId" :disabled="!stateId" class="{{ $selectClass }}">
                <option value="">Seleccione una ciudad</option>
                <template x-for="city in filteredCities" :key="city.Id">
                    <option :value="String(city.Id)" x-text="city.is_capital ? `${city.name} (capital)` : city.name"></option>
                </template>
            </select>
            @error('cities_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <flux:input
            name="address"
            label="Dirección"
            :value="old('address', $enterprise->address ?? '')"
            maxlength="255"
        />

        <flux:input
            name="tax_regime"
            label="Régimen tributario"
            :value="old('tax_regime', $enterprise->tax_regime ?? '')"
            maxlength="120"
        />
    </div>

    <div class="flex flex-wrap gap-3">
        <flux:button variant="primary" type="submit" icon="check">
            Guardar
        </flux:button>

        <flux:button variant="filled" :href="$cancelUrl" icon="x-mark">
            Cancelar
        </flux:button>
    </div>
</form>
