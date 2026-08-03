<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin Test', 'email' => 'admin-test@example.com',
            'password' => 'password', 'role' => 'super_admin',
        ]);
    }

    public function test_administrator_can_open_service_dashboard(): void
    {
        $this->actingAs($this->admin())->get(route('admin.dashboard'))
            ->assertOk()->assertSee('Pilotage des prestations')->assertSee('Services publiés')
            ->assertDontSee('Chiffre d’affaires')->assertDontSee('stock');
    }

    public function test_administrator_can_create_a_service(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.services.store'), [
            'name' => 'Infogérance',
            'summary' => 'Supervision et assistance de votre système informatique.',
            'description' => 'Une prestation complète de suivi, prévention et support.',
            'deliverables' => "Supervision\nRapport mensuel\nAssistance utilisateurs",
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['slug' => 'infogerance', 'is_published' => true]);
        $this->assertSame(['Supervision', 'Rapport mensuel', 'Assistance utilisateurs'], Service::first()->deliverables);
    }
}
