<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Setting::class);

        return Setting::all();
    }

    public function store(SettingRequest $request)
    {
        $this->authorize('create', Setting::class);

        return Setting::create($request->validated());
    }

    public function show(Setting $setting)
    {
        $this->authorize('view', $setting);

        return $setting;
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        $this->authorize('update', $setting);

        $setting->update($request->validated());

        return $setting;
    }

    public function destroy(Setting $setting)
    {
        $this->authorize('delete', $setting);

        $setting->delete();

        return response()->json();
    }
}
