<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\TrainingRegistration;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $days = in_array((int) $request->input('period', 30), [7, 30, 90, 365], true) ? (int) $request->input('period', 30) : 30;
        $periodQuotes = QuoteRequest::where('created_at', '>=', now()->subDays($days)->startOfDay())->get();

        return view('admin.dashboard', [
            'days' => $days,
            'metrics' => [
                'services' => Service::count(),
                'publishedServices' => Service::where('is_published', true)->count(),
                'quotes' => $periodQuotes->count(),
                'newQuotes' => QuoteRequest::where('status', 'Nouveau')->count(),
                'messages' => ContactMessage::where('status', 'Nouveau')->count(),
                'registrations' => TrainingRegistration::where('status', 'Nouvelle')->count(),
                'applications' => Application::where('status', 'Nouvelle')->count(),
                'quoteConversion' => $periodQuotes->count() ? round($periodQuotes->where('status', 'Clôturé')->count() / $periodQuotes->count() * 100) : 0,
            ],
            'quotes' => QuoteRequest::latest()->take(6)->get(),
            'messages' => ContactMessage::latest()->take(4)->get(),
            'applications' => Application::with('job')->latest()->take(4)->get(),
            'registrations' => TrainingRegistration::with('training')->latest()->take(4)->get(),
        ]);
    }
}
