<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_service_oriented(): void
    {
        $this->get('/')->assertOk()->assertSee('services informatiques');
    }

    public function test_service_page_contains_whatsapp_request_with_service_name(): void
    {
        $service = Service::create([
            'name' => 'Audit de sécurité',
            'slug' => 'audit-securite',
            'summary' => 'Évaluation complète de votre environnement.',
            'description' => 'Nous analysons les risques et proposons un plan d’action.',
            'is_published' => true,
        ]);

        $this->get(route('services.show', $service))
            ->assertOk()
            ->assertSee('Demander ce service')
            ->assertSee(rawurlencode('Audit de sécurité'), false);
    }

    public function test_quote_form_preselects_requested_service(): void
    {
        $service = Service::create([
            'name' => 'Audit informatique',
            'slug' => 'audit-informatique',
            'summary' => 'Diagnostic du système.',
            'description' => 'Analyse détaillée et recommandations.',
            'is_published' => true,
        ]);

        $this->get(route('quote', ['service' => $service->slug]))
            ->assertOk()
            ->assertSee('<option value="Audit informatique" selected>', false);
    }

    public function test_quote_is_saved_before_confirmation(): void
    {
        $this->post('/devis', [
            'name' => 'Client Test', 'email' => 'client@example.com', 'phone' => '+237600000000',
            'city' => 'Douala', 'service' => 'Audit informatique',
            'description' => 'Nous souhaitons un audit complet de notre parc informatique.',
            'consent' => '1',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('quote_requests', ['email' => 'client@example.com']);
    }
}
