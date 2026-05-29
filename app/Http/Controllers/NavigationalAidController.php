<?php

namespace App\Http\Controllers;

use App\Models\NavigationalAid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NavigationalAidController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $aids = NavigationalAid::query()
            ->when($search, function ($query) use ($search) {
                $query->where('aid_name', 'like', "%{$search}%")
                    ->orWhere('aid_type', 'like', "%{$search}%")
                    ->orWhere('frequency', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('navigational-aids.index', compact('aids', 'search'));
    }

    public function create()
    {
        return view('navigational-aids.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'aid_name' => ['required', 'string', 'max:255'],
            'aid_type' => ['required', 'in:VOR,NDB,DME'],
            'frequency' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string'],
        ]);

        $data['user_id'] = $request->user()->id;

        NavigationalAid::create($data);

        return redirect()->route('navigational-aids.index')->with('status', 'Navigational aid created successfully.');
    }

    public function edit(NavigationalAid $navigationalAid)
    {
        return view('navigational-aids.edit', ['aid' => $navigationalAid]);
    }

    public function update(Request $request, NavigationalAid $navigationalAid): RedirectResponse
    {
        $data = $request->validate([
            'aid_name' => ['required', 'string', 'max:255'],
            'aid_type' => ['required', 'in:VOR,NDB,DME'],
            'frequency' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string'],
        ]);

        $navigationalAid->update($data);

        return redirect()->route('navigational-aids.index')->with('status', 'Navigational aid updated successfully.');
    }

    public function destroy(NavigationalAid $navigationalAid): RedirectResponse
    {
        $navigationalAid->delete();

        return redirect()->route('navigational-aids.index')->with('status', 'Navigational aid deleted successfully.');
    }
}