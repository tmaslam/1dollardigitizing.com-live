<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Support\AdminNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminSiteContactController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Schema::hasTable('sites'), 404);

        return view('admin.tools.site-contact.index', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'sites'     => Site::query()->active()->orderByDesc('is_primary')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, int $site)
    {
        abort_unless(Schema::hasTable('sites'), 404);

        $validated = $request->validate([
            'phone_number'    => ['nullable', 'string', 'max:100'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'uk_phone_number' => ['nullable', 'string', 'max:100'],
            'uk_address'      => ['nullable', 'string', 'max:500'],
            'pk_phone_number' => ['nullable', 'string', 'max:100'],
            'pk_address'      => ['nullable', 'string', 'max:500'],
        ]);

        $siteModel = Site::query()->findOrFail($site);
        $siteModel->update([
            'phone_number'    => trim((string) ($validated['phone_number'] ?? '')),
            'company_address' => trim((string) ($validated['company_address'] ?? '')),
            'uk_phone_number' => trim((string) ($validated['uk_phone_number'] ?? '')),
            'uk_address'      => trim((string) ($validated['uk_address'] ?? '')),
            'pk_phone_number' => trim((string) ($validated['pk_phone_number'] ?? '')),
            'pk_address'      => trim((string) ($validated['pk_address'] ?? '')),
        ]);

        return redirect()->to(url('/v/site-contact.php'))
            ->with('success', 'Contact details for "'.(($siteModel->brand_name ?: $siteModel->name) ?: $siteModel->legacy_key).'" updated.');
    }
}
