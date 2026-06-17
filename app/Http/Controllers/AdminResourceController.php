<?php

namespace App\Http\Controllers;

use App\Support\AdminResourceRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class AdminResourceController extends Controller
{
    protected string $resourceKey;

    public function index(Request $request): View
    {
        $resource = $this->resource();
        $search = trim((string) $request->query('search', ''));
        $selectedRecord = $this->selectedRecord($request);

        $records = $this->query()
            ->when($search !== '', function (Builder $query) use ($resource, $search): void {
                $query->where(function (Builder $query) use ($resource, $search): void {
                    foreach ($resource['search'] as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
            })
            ->latest($this->primaryKey())
            ->paginate($resource['per_page'])
            ->withQueryString();

        return view('admin-resources.index', [
            'resource' => $resource,
            'records' => $records,
            'search' => $search,
            'stats' => $this->stats(),
            'options' => $this->options($resource),
            'selectedRecord' => $selectedRecord,
        ]);
    }

    public function create(): View
    {
        $resource = $this->resource();

        return view('admin-resources.create', [
            'resource' => $resource,
            'record' => null,
            'options' => $this->options($resource),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $resource = $this->resource();
        $data = $this->normalizedData($request, AdminResourceRegistry::rules($this->resourceKey, null, $request));

        /** @var class-string<Model> $model */
        $model = $resource['model'];
        $record = $model::query()->create($data);

        return redirect()
            ->route($resource['route'].'.index', ['show' => $record->getKey()])
            ->with('status', "El {$resource['singular']} fue creado correctamente.");
    }

    public function show(int|string $record): RedirectResponse
    {
        $resource = $this->resource();

        $this->findRecord($record);

        return redirect()->route($resource['route'].'.index', ['show' => $record]);
    }

    public function edit(int|string $record): View
    {
        $resource = $this->resource();

        return view('admin-resources.edit', [
            'resource' => $resource,
            'record' => $this->findRecord($record),
            'options' => $this->options($resource),
        ]);
    }

    public function update(Request $request, int|string $record): RedirectResponse
    {
        $resource = $this->resource();
        $model = $this->findRecord($record);
        $data = $this->normalizedData($request, AdminResourceRegistry::rules($this->resourceKey, $model, $request), false);

        $model->update($data);

        return redirect()
            ->route($resource['route'].'.index', ['show' => $model->getKey()])
            ->with('status', "El {$resource['singular']} fue actualizado correctamente.");
    }

    public function destroy(int|string $record): RedirectResponse
    {
        $resource = $this->resource();
        $model = $this->findRecord($record);
        $model->loadCount($resource['dependencies']);

        foreach ($resource['dependencies'] as $dependency) {
            if ((int) $model->getAttribute($dependency.'_count') > 0) {
                return redirect()
                    ->route($resource['route'].'.index')
                    ->with('status', "No se puede eliminar el {$resource['singular']} porque tiene registros asociados.");
            }
        }

        $model->delete();

        return redirect()
            ->route($resource['route'].'.index')
            ->with('status', "El {$resource['singular']} fue eliminado correctamente.");
    }

    /**
     * @return array<string, mixed>
     */
    protected function resource(): array
    {
        return AdminResourceRegistry::get($this->resourceKey);
    }

    protected function query(): Builder
    {
        $resource = $this->resource();

        /** @var class-string<Model> $model */
        $model = $resource['model'];

        return $model::query()
            ->with($resource['with'])
            ->withCount($resource['with_count']);
    }

    protected function findRecord(int|string $record): Model
    {
        return $this->query()->findOrFail($record);
    }

    /**
     * @param  array<string, mixed>  $rules
     * @return array<string, mixed>
     */
    protected function normalizedData(Request $request, array $rules, bool $creating = true): array
    {
        $data = $request->validate($rules);
        $resource = $this->resource();

        foreach ($resource['fields'] as $field) {
            if (($field['type'] ?? null) === 'boolean') {
                $data[$field['name']] = $request->boolean($field['name']);
            }
        }

        if ($creating && $this->hasField('team_id')) {
            $data['team_id'] = Auth::user()?->currentTeam?->id;
        }

        if ($creating && $this->hasField('created_by')) {
            $data['created_by'] = Auth::id();
        }

        if ($this->hasField('updated_by')) {
            $data['updated_by'] = Auth::id();
        }

        return $data;
    }

    protected function hasField(string $field): bool
    {
        /** @var class-string<Model> $model */
        $model = $this->resource()['model'];

        return in_array($field, (new $model)->getFillable(), true);
    }

    /**
     * @param  array<string, mixed>  $resource
     * @return array<string, array<int, array{value: string, label: string}>>
     */
    protected function options(array $resource): array
    {
        $optionKeys = collect($resource['fields'])
            ->pluck('options')
            ->filter()
            ->unique()
            ->values();

        return $optionKeys
            ->mapWithKeys(fn (string $optionKey): array => [$optionKey => AdminResourceRegistry::options($optionKey)])
            ->all();
    }

    /**
     * @return array<string, int>
     */
    protected function stats(): array
    {
        $resource = $this->resource();
        $model = $resource['model'];

        $stats = [
            'total' => $model::query()->count(),
        ];

        if (collect($resource['fields'])->contains(fn (array $field): bool => $field['name'] === 'is_active')) {
            $stats['active'] = $model::query()->where('is_active', true)->count();
        }

        if (collect($resource['fields'])->contains(fn (array $field): bool => $field['name'] === 'is_default')) {
            $stats['default'] = $model::query()->where('is_default', true)->count();
        }

        return $stats;
    }

    protected function selectedRecord(Request $request): ?Model
    {
        $selectedId = $request->query('show');

        if (! $selectedId) {
            return null;
        }

        return $this->findRecord($selectedId);
    }

    protected function primaryKey(): string
    {
        /** @var class-string<Model> $model */
        $model = $this->resource()['model'];

        return (new $model)->getKeyName();
    }
}
