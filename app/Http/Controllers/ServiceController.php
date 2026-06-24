<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $services = Service::query()
            ->with('currency')
            ->withCount(['planItems', 'serviceRates'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->latest('Id')
            ->paginate(12)
            ->withQueryString();

        return view('services.index', compact('services', 'search'));
    }

    public function create(): View
    {
        return view('services.create', [
            'currencies' => $this->currencies(),
            'pricingTypes' => $this->pricingTypes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        $validated['team_id'] = Auth::user()?->currentTeam?->id;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $service = Service::query()->create($validated);

        return redirect()
            ->route('services.edit', $service)
            ->with('status', 'Servicio creado correctamente.');
    }

    public function show(Service $service): RedirectResponse
    {
        return redirect()->route('services.edit', $service);
    }

    public function edit(Service $service): View
    {
        return view('services.edit', [
            'service' => $service,
            'currencies' => $this->currencies(),
            'pricingTypes' => $this->pricingTypes(),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $this->validatedData($request, $service);
        $validated['updated_by'] = Auth::id();

        $service->update($validated);

        return redirect()
            ->route('services.edit', $service)
            ->with('status', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->loadCount(['planItems', 'serviceRates']);

        if ($service->plan_items_count > 0 || $service->service_rates_count > 0) {
            return redirect()
                ->route('services.index')
                ->with('status', 'No se puede eliminar el servicio porque tiene planes o tarifas asociadas.');
        }

        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('status', 'Servicio eliminado correctamente.');
    }

    private function currencies(): Collection
    {
        return Currency::query()
            ->orderBy('code')
            ->get(['Id', 'code', 'name']);
    }

    /**
     * @return array<string, string>
     */
    private function pricingTypes(): array
    {
        return [
            'fixed' => 'Fijo',
            'hourly' => 'Por hora',
            'monthly' => 'Mensual',
            'custom' => 'Personalizado',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Service $service = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('services', 'code')->ignore($service?->getKey(), 'Id')],
            'name' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:120'],
            'pricing_type' => ['required', Rule::in(array_keys($this->pricingTypes()))],
            'unit' => ['required', 'string', 'max:40'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['nullable', 'exists:currencies,Id'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
