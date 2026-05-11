<x-layouts::app>
    <div class="mb-4 flex items-center justify-between">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('enterprises.index')">{{ __('Enterprises') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Create') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    @php
        $selectClass = 'mt-2 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 disabled:cursor-not-allowed disabled:bg-zinc-100 disabled:text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:focus:border-sky-400 dark:focus:ring-sky-400/30';
    @endphp

    <form
        action="{{ route('enterprises.store') }}"
        method="POST"
        class="w-full space-y-6 md:w-1/4 md:min-w-[20rem]"
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

        <div>
            <label for="document_types_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                {{ __('Document type') }}
            </label>
            <select id="document_types_Id" name="document_types_Id" required class="{{ $selectClass }}">
                <option value="">{{ __('Select a document type') }}</option>
                @foreach ($documentTypes as $documentType)
                    <option value="{{ $documentType->getKey() }}" @selected(old('document_types_Id') == $documentType->getKey())>{{ $documentType->name }}</option>
                @endforeach
            </select>
            @error('document_types_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <flux:input
            name="document_number"
            :label="__('Document number')"
            :value="old('document_number')"
            required
            maxlength="50"
            autofocus
        />

        <flux:input
            name="legal_name"
            :label="__('Legal name')"
            :value="old('legal_name')"
            required
            maxlength="180"
        />

        <flux:input
            name="trade_name"
            :label="__('Trade name')"
            :value="old('trade_name')"
            maxlength="180"
        />

        <flux:input
            name="email"
            :label="__('Email')"
            type="email"
            :value="old('email')"
            maxlength="180"
        />

        <flux:input
            name="phone"
            :label="__('Phone')"
            :value="old('phone')"
            maxlength="40"
        />

        <div>
            <label for="countries_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                {{ __('Country') }}
            </label>
            <select id="countries_Id" name="countries_Id" x-model="countryId" x-on:change="changeCountry" required class="{{ $selectClass }}">
                <option value="">{{ __('Select a country') }}</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->getKey() }}">{{ $country->name }}</option>
                @endforeach
            </select>
            @error('countries_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="states_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                {{ __('State') }}
            </label>
            <select id="states_Id" name="states_Id" x-model="stateId" x-on:change="changeState" :disabled="!countryId" class="{{ $selectClass }}">
                <option value="">{{ __('Select a state') }}</option>
                <template x-for="state in filteredStates" :key="state.Id">
                    <option :value="String(state.Id)" x-text="state.name"></option>
                </template>
            </select>
            @error('states_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="cities_Id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                {{ __('City') }}
            </label>
            <select id="cities_Id" name="cities_Id" x-model="cityId" :disabled="!stateId" class="{{ $selectClass }}">
                <option value="">{{ __('Select a city') }}</option>
                <template x-for="city in filteredCities" :key="city.Id">
                    <option :value="String(city.Id)" x-text="city.is_capital ? `${city.name} (capital)` : city.name"></option>
                </template>
            </select>
            @error('cities_Id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <flux:input
            name="address"
            :label="__('Address')"
            :value="old('address')"
            maxlength="255"
        />

        <flux:input
            name="tax_regime"
            :label="__('Tax regime')"
            :value="old('tax_regime')"
            maxlength="120"
        />

        <div class="flex gap-2">
            <flux:button variant="primary" type="submit">
                {{ __('Save') }}
            </flux:button>

            <flux:button variant="filled" :href="route('enterprises.index')">
                {{ __('Cancel') }}
            </flux:button>
        </div>
    </form>
</x-layouts::app>
