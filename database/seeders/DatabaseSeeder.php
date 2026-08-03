<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Identifiants issus de la configuration : le dépôt étant public, aucun
        // mot de passe de production ne doit être écrit en dur ici.
        User::updateOrCreate(['email' => config('tntmtech.admin.email')], [
            'name' => config('tntmtech.admin.name'),
            'password' => Hash::make(config('tntmtech.admin.password')),
            'role' => 'super_admin',
            'agency' => 'douala',
        ]);

        foreach ([
            ['Audit informatique','audit-informatique','Diagnostic & conseil','Identifiez les risques et priorisez vos investissements.','Nous réalisons un inventaire structuré de votre environnement, évaluons les risques et produisons un plan d’action réaliste.',['Inventaire des équipements et usages','Cartographie des risques','Plan d’action priorisé','Restitution à la direction'],'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1000&q=80'],
            ['Réseaux & solutions IT','reseaux-solutions-it','Infrastructure','Connectez vos équipes avec une infrastructure performante et sécurisée.','De l’étude de couverture au câblage et à la documentation, nous déployons une infrastructure exploitable et évolutive.',['Étude et schéma réseau','Câblage et installation Wi-Fi','Configuration et sécurisation','Documentation technique'],'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=1000&q=80'],
            ['Maintenance & assistance','maintenance-assistance','Continuité de service','Réduisez les interruptions et prolongez la durée de vie de votre parc.','Nous diagnostiquons, réparons et maintenons vos postes avec un suivi clair des interventions.',['Diagnostic matériel et logiciel','Réparation et mise à niveau','Maintenance préventive','Compte rendu d’intervention'],'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?auto=format&fit=crop&w=1000&q=80'],
            ['Services web & logiciels','services-web-logiciels','Transformation digitale','Créez des outils numériques alignés sur votre activité.','Nous concevons des sites, applications métier et intégrations qui simplifient réellement vos opérations.',['Cadrage fonctionnel','Design responsive','Développement sécurisé','Formation et mise en ligne'],'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1000&q=80'],
            ['Cybersécurité','cybersecurite','Protection','Renforcez vos pratiques et vos configurations essentielles.','Nous évaluons les vulnérabilités courantes, sécurisons les accès et sensibilisons les utilisateurs.',['Évaluation des risques','Durcissement des postes et réseaux','Gestion des accès','Sensibilisation des équipes'],'https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=1000&q=80'],
            ['Formation professionnelle','formation-professionnelle','Compétences','Rendez vos équipes plus autonomes et efficaces.','Nos formations privilégient la pratique, les cas concrets et le transfert immédiat dans le travail.',['Évaluation du niveau','Programme adapté','Exercices pratiques','Attestation de participation'],'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1000&q=80'],
        ] as $service) {
            Service::updateOrCreate(['slug' => $service[1]], [
                'name' => $service[0], 'eyebrow' => $service[2], 'summary' => $service[3],
                'description' => $service[4], 'deliverables' => $service[5],
                'image' => $service[6], 'is_published' => true,
            ]);
        }

        foreach ([
            ['Administration systèmes & réseaux','administration-systemes-reseaux','TNT-F01','Maîtrisez les fondamentaux pour administrer une infrastructure professionnelle.','Techniciens débutants et responsables IT','Notions générales en informatique','5 jours','Présentiel',250000,['Modèles et protocoles réseau','Adressage IP et sous-réseaux','Configuration des équipements','Diagnostic et sécurisation']],
            ['Créer un site web moderne','creation-site-web','TNT-F03','Passez d’une idée à un site responsive publié en ligne.','Étudiants, entrepreneurs et développeurs débutants','Utilisation courante d’un ordinateur','6 semaines','Hybride',300000,['HTML sémantique','CSS responsive','JavaScript interactif','PHP, base de données et déploiement']],
        ] as $training) {
            Training::updateOrCreate(['code' => $training[2]], [
                'title' => $training[0], 'slug' => $training[1], 'summary' => $training[3],
                'audience' => $training[4], 'prerequisites' => $training[5], 'duration' => $training[6],
                'format' => $training[7], 'price' => $training[8], 'program' => $training[9],
                'is_published' => true,
            ]);
        }

        Job::updateOrCreate(['slug' => 'technicien-support-it-douala'], [
            'title' => 'Technicien·ne support IT', 'city' => 'Douala', 'contract_type' => 'CDI',
            'description' => 'Vous accompagnez nos clients dans le diagnostic, la maintenance et la mise en service de leurs environnements.',
            'missions' => ['Diagnostiquer les incidents matériels et logiciels','Préparer et installer les postes clients','Documenter les interventions','Conseiller les utilisateurs'],
            'deadline' => now()->addMonth(), 'is_published' => true,
        ]);

        QuoteRequest::updateOrCreate(['reference' => 'DEV-260724-DEMO1'], [
            'name' => 'Alain M.', 'company' => 'Camer Services', 'email' => 'alain@example.com',
            'phone' => '+237 699 11 22 33', 'city' => 'Yaoundé', 'service' => 'Réseaux & solutions IT',
            'description' => 'Déploiement d’un réseau Wi-Fi sécurisé pour deux étages et environ 35 collaborateurs.',
            'budget' => '500 000 à 2 000 000 FCFA', 'status' => 'Nouveau',
        ]);
    }
}
