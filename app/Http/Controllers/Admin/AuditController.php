<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        $logs = Activity::with(['causer', 'subject'])
            ->when(request('causer_id'), fn($q) => $q->where('causer_id', request('causer_id')))
            ->when(request('log_name'), fn($q) => $q->where('log_name', request('log_name')))
            ->when(request('date'), fn($q) => $q->whereDate('created_at', request('date')))
            ->latest()
            ->paginate(30);

        $logNames = Activity::select('log_name')->distinct()->pluck('log_name');

        return view('admin.audit.index', compact('logs', 'logNames'));
    }
}
