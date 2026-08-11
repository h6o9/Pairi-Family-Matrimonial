<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LookupOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LookupController extends Controller
{
    public function index(string $type)
    {
        $definition = $this->definition($type);

        return view('admin.lookups.index', compact('type', 'definition'));
    }

    public function data(Request $request, string $type): JsonResponse
    {
        $definition = $this->definition($type);
        $model = LookupOption::fromTable($definition['table']);
        $query = $model->newQuery();
        $recordsTotal = (clone $query)->count();
        $search = trim((string) $request->input('search.value', ''));

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $recordsFiltered = (clone $query)->count();
        $start = max(0, (int) $request->input('start', 0));
        $length = min(100, max(10, (int) $request->input('length', 10)));
        $orderColumns = ['id', 'name', 'status'];
        $orderIndex = (int) $request->input('order.0.column', 0);
        $orderColumn = $orderColumns[$orderIndex] ?? 'id';
        $orderDirection = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $items = $query
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get(['id', 'name', 'status']);

        $rows = $items->values()->map(function (LookupOption $item, int $index) use ($type, $start) {
            $editUrl = route('admin.lookups.edit', ['type' => $type, 'id' => $item->id]);
            $deleteUrl = route('admin.lookups.destroy', ['type' => $type, 'id' => $item->id]);
            $badgeClass = $item->status === 'active' ? 'success' : 'danger';

            return [
                'row_number' => $start + $index + 1,
                'name' => e($item->name),
                'status' => '<span class="badge badge-'.$badgeClass.'">'.e(ucfirst($item->status)).'</span>',
                'action' => '<div class="table-actions">'
                    .'<a href="'.$editUrl.'" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-edit"></i></a>'
                    .'<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm deleteForm" data-url="'.$deleteUrl.'" title="Delete"><i class="fa fa-trash"></i></a>'
                    .'</div>',
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    public function create(string $type)
    {
        $definition = $this->definition($type);

        return view('admin.lookups.create', compact('type', 'definition'));
    }

    public function store(Request $request, string $type)
    {
        $definition = $this->definition($type);
        $data = $this->validated($request, $definition['table']);
        $data = $this->prepareData($data, $definition['table']);

        LookupOption::fromTable($definition['table'])->newQuery()->create($data);
        $this->clearApiCache($type);

        return redirect()->route('admin.lookups.index', ['type' => $type])->with([
            'message' => $definition['label'].' created successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function edit(string $type, int $id)
    {
        $definition = $this->definition($type);
        $item = LookupOption::fromTable($definition['table'])->newQuery()->findOrFail($id);

        return view('admin.lookups.edit', compact('type', 'definition', 'item'));
    }

    public function update(Request $request, string $type, int $id)
    {
        $definition = $this->definition($type);
        $item = LookupOption::fromTable($definition['table'])->newQuery()->findOrFail($id);
        $data = $this->validated($request, $definition['table'], $id);
        $item->update($this->prepareData($data, $definition['table']));
        $this->clearApiCache($type);

        return redirect()->route('admin.lookups.index', ['type' => $type])->with([
            'message' => $definition['label'].' updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(string $type, int $id)
    {
        $definition = $this->definition($type);
        LookupOption::fromTable($definition['table'])->newQuery()->findOrFail($id)->delete();
        $this->clearApiCache($type);

        return redirect()->route('admin.lookups.index', ['type' => $type])->with([
            'message' => $definition['label'].' deleted successfully.',
            'alert-type' => 'success',
        ]);
    }

    private function definition(string $type): array
    {
        $definition = config("profile_lookups.{$type}");
        abort_unless(is_array($definition), 404);

        return $definition;
    }

    private function validated(Request $request, string $table, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique($table, 'name')->ignore($ignoreId)],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }

    private function prepareData(array $data, string $table): array
    {
        if ($table === 'countries') {
            $letters = preg_replace('/[^A-Za-z]/', '', $data['name']) ?: 'CTR';
            $data['code'] = strtoupper(substr($letters, 0, 3));
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }

    private function clearApiCache(string $type): void
    {
        Cache::forget('api_lookup_'.$type);
        Cache::forget('api_profile_options');
        Cache::forget('marriage_bureau_form_lookups');

        if ($type === 'countries') {
            Cache::forget('api_countries');
        }
    }
}
