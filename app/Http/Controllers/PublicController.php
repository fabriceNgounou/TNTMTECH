<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ContactMessage;
use App\Models\Job;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Training;
use App\Models\TrainingRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function services() { return view('services.index', ['services' => Service::where('is_published', true)->get()]); }
    public function service(Service $service) { abort_unless($service->is_published, 404); return view('services.show', compact('service')); }
    public function trainings() { return view('trainings.index', ['trainings' => Training::where('is_published', true)->get()]); }
    public function training(Training $training) { abort_unless($training->is_published, 404); return view('trainings.show', compact('training')); }
    public function jobs() { return view('jobs.index', ['jobs' => Job::where('is_published', true)->latest()->get()]); }
    public function job(Job $job) { abort_unless($job->is_published, 404); return view('jobs.show', compact('job')); }
    public function page(string $page) { abort_unless(in_array($page, ['entreprise', 'realisations', 'confidentialite']), 404); return view("pages.{$page}"); }
    public function information() { return view('pages.informations'); }
    public function quote(Request $request)
    {
        $services = Service::where('is_published', true)->orderBy('name')->get();
        $selectedService = $services->firstWhere('slug', $request->query('service'))?->name;
        return view('quote', compact('services', 'selectedService'));
    }
    public function contact() { return view('contact'); }

    public function storeQuote(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'], 'company' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email'], 'phone' => ['required', 'string', 'max:30'],
            'city' => ['required', 'string'], 'service' => ['required', 'string'],
            'description' => ['required', 'string', 'min:20'], 'budget' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
            'consent' => ['accepted'],
        ]);
        $data['reference'] = 'DEV-'.now()->format('ymd').'-'.strtoupper(Str::random(5));
        if ($request->hasFile('attachment')) $data['attachment'] = $request->file('attachment')->store('quotes', 'local');
        $quote = QuoteRequest::create($data);
        return back()->with('success', "Votre demande {$quote->reference} est enregistrée. Notre équipe vous contactera.");
    }

    public function storeContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email'],
            'phone' => ['nullable', 'string'], 'agency' => ['required', 'in:douala,yaounde,france'],
            'subject' => ['required', 'string', 'max:160'], 'message' => ['required', 'string', 'min:10'],
        ]);
        ContactMessage::create($data);
        return back()->with('success', 'Votre message a bien été transmis.');
    }

    public function registerTraining(Request $request, Training $training)
    {
        $data = $request->validate([
            'name' => ['required', 'string'], 'email' => ['required', 'email'], 'phone' => ['required', 'string'],
            'city' => ['required', 'string'], 'company' => ['nullable', 'string'], 'message' => ['nullable', 'string'],
            'consent' => ['accepted'],
        ]);
        $training->registrations()->create($data);
        return back()->with('success', 'Préinscription enregistrée. Nous vous confirmerons la prochaine session.');
    }

    public function apply(Request $request, ?Job $job = null)
    {
        $data = $request->validate([
            'name' => ['required', 'string'], 'email' => ['required', 'email'], 'phone' => ['required', 'string'],
            'city' => ['required', 'string'], 'message' => ['nullable', 'string'],
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], 'consent' => ['accepted'],
        ]);
        $data['job_id'] = $job?->id;
        $data['cv_path'] = $request->file('cv')->store('applications', 'local');
        Application::create($data);
        return back()->with('success', 'Votre candidature a bien été reçue.');
    }
}
