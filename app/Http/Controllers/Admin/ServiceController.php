<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index() { return view('admin.services.index', ['services' => Service::orderBy('name')->paginate(20)]); }
    public function create() { return view('admin.services.form', ['service' => new Service()]); }
    public function edit(Service $service) { return view('admin.services.form', compact('service')); }

    public function store(Request $request)
    {
        Service::create($this->validated($request));
        return redirect()->route('admin.services.index')->with('success', 'Service créé.');
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->validated($request, $service));
        return redirect()->route('admin.services.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service supprimé.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        $request->merge(['slug' => $request->input('slug') ?: Str::slug($request->input('name', ''))]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'string', 'max:180', Rule::unique('services')->ignore($service)],
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'deliverables' => ['nullable', 'string'],
            'image' => ['nullable', 'url', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        $data['deliverables'] = collect(preg_split('/\r\n|\r|\n/', $data['deliverables'] ?? ''))->map(fn ($item) => trim($item))->filter()->values()->all();
        $data['is_published'] = $request->boolean('is_published');
        return $data;
    }
}
