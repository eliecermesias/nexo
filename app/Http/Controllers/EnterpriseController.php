<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enterprises = Enterprise::query()
            ->latest('Id')
            ->paginate(15);

        return view('enterprises.index', compact('enterprises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentTypes = DocumentType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['Id', 'name']);

        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['Id', 'name']);

        $states = State::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['Id', 'countries_Id', 'name']);

        $cities = City::query()
            ->where('is_active', true)
            ->orderByDesc('is_capital')
            ->orderBy('name')
            ->get(['Id', 'states_Id', 'name', 'is_capital']);

        $selectedCountryId = old('countries_Id') ?? $countries->first()?->getKey();
        $selectedStateId = old('states_Id');
        $selectedCityId = old('cities_Id');

        return view('enterprises.create', compact(
            'documentTypes',
            'countries',
            'states',
            'cities',
            'selectedCountryId',
            'selectedStateId',
            'selectedCityId',
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_types_Id' => ['required', 'exists:document_types,Id'],
            'document_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('enterprises', 'document_number')
                    ->where(fn ($query) => $query->where('document_types_Id', $request->input('document_types_Id'))),
            ],
            'legal_name' => ['required', 'string', 'max:180'],
            'trade_name' => ['nullable', 'string', 'max:180'],
            'email' => ['nullable', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'countries_Id' => ['required', 'exists:countries,Id'],
            'states_Id' => ['nullable', 'exists:states,Id'],
            'cities_Id' => ['nullable', 'exists:cities,Id'],
            'tax_regime' => ['nullable', 'string', 'max:120'],
        ]);

        $country = Country::query()->findOrFail($validated['countries_Id']);
        $state = empty($validated['states_Id']) ? null : State::query()->findOrFail($validated['states_Id']);
        $city = empty($validated['cities_Id']) ? null : City::query()->findOrFail($validated['cities_Id']);

        if ($state && $state->countries_Id !== $country->getKey()) {
            return back()
                ->withErrors(['states_Id' => 'El departamento/estado seleccionado no pertenece al pais.'])
                ->withInput();
        }

        if ($city && (! $state || $city->states_Id !== $state->getKey())) {
            return back()
                ->withErrors(['cities_Id' => 'La ciudad seleccionada no pertenece al departamento/estado.'])
                ->withInput();
        }

        $validated['country'] = $country->name;
        $validated['state'] = $state?->name;
        $validated['city'] = $city?->name;

        $enterprise = Enterprise::query()->create($validated);

        return redirect()
            ->route('enterprises.show', $enterprise)
            ->with('status', __('Enterprise created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Enterprise $enterprise)
    {
        return view('enterprises.show', compact('enterprise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enterprise $enterprise)
    {
        $countries = Country::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['Id', 'name']);

        $states = State::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['Id', 'countries_Id', 'name']);

        $cities = City::query()
            ->where('is_active', true)
            ->orderByDesc('is_capital')
            ->orderBy('name')
            ->get(['Id', 'states_Id', 'name', 'is_capital']);

        $selectedCountryId = old('countries_Id', $enterprise->countries_Id)
            ?? $countries->firstWhere('name', $enterprise->country)?->getKey()
            ?? $countries->first()?->getKey();

        $selectedStateId = old('states_Id', $enterprise->states_Id)
            ?? $states->firstWhere('name', $enterprise->state)?->getKey();

        $selectedCityId = old('cities_Id', $enterprise->cities_Id)
            ?? $cities
                ->where('states_Id', $selectedStateId)
                ->firstWhere('name', $enterprise->city)?->getKey();

        return view('enterprises.edit', compact(
            'enterprise',
            'countries',
            'states',
            'cities',
            'selectedCountryId',
            'selectedStateId',
            'selectedCityId',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enterprise $enterprise)
    {
        $validated = $request->validate([
            'document_number' => ['required', 'string', 'max:50'],
            'legal_name' => ['required', 'string', 'max:180'],
            'trade_name' => ['nullable', 'string', 'max:180'],
            'email' => ['nullable', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'countries_Id' => ['required', 'exists:countries,Id'],
            'states_Id' => ['nullable', 'exists:states,Id'],
            'cities_Id' => ['nullable', 'exists:cities,Id'],
            'tax_regime' => ['nullable', 'string', 'max:120'],
        ]);

        $country = Country::query()->findOrFail($validated['countries_Id']);
        $state = empty($validated['states_Id']) ? null : State::query()->findOrFail($validated['states_Id']);
        $city = empty($validated['cities_Id']) ? null : City::query()->findOrFail($validated['cities_Id']);

        if ($state && $state->countries_Id !== $country->getKey()) {
            return back()
                ->withErrors(['states_Id' => 'El departamento/estado seleccionado no pertenece al pais.'])
                ->withInput();
        }

        if ($city && (! $state || $city->states_Id !== $state->getKey())) {
            return back()
                ->withErrors(['cities_Id' => 'La ciudad seleccionada no pertenece al departamento/estado.'])
                ->withInput();
        }

        $validated['country'] = $country->name;
        $validated['state'] = $state?->name;
        $validated['city'] = $city?->name;

        $enterprise->update($validated);

        return redirect()
            ->route('enterprises.show', $enterprise)
            ->with('status', 'Empresa actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enterprise $enterprise)
    {
        //
    }
}
