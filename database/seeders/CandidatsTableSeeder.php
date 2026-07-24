<?php

namespace Database\Seeders;

use App\Models\Candidat;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CandidatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Données réalistes pour les candidats
        $villes = ['Dakar', 'Thiès', 'Kaolack', 'Tambacounda', 'Saint-Louis', 'Ziguinchor', 'Kolda', 'Diourbel', 'Guédiawaye', 'Pikine'];
        $niveaux = ['Bac', 'Licence', 'Master', 'Doctorat', 'BTS', 'DUT'];
        $piedsForts = ['gauche', 'droit', 'les deux'];
        $langues = ['Français', 'Anglais', 'Espagnol'];
        $niveauxLangues = ['Débutant', 'Intermédiaire', 'Avancé'];

        // Générer 100 candidats
        for ($i = 0; $i < 100; $i++) {
            // Générer une date de naissance entre 18 et 45 ans
            $dateNaissance = $faker->dateTimeBetween('-45 years', '-18 years');

            // Sélectionner les langues aléatoirement (1 à 3)
            $languesSelectionnees = $faker->randomElements($langues, $faker->numberBetween(1, 3));

            // Créer le candidat
            Candidat::create([
                'nom'              => $faker->lastName(),
                'prenom'           => $faker->firstName(),
                'date_naissance'   => $dateNaissance,
                'age'              => Carbon::parse($dateNaissance)->age,
                'lieu_naissance'   => $faker->randomElement($villes),
                'telephone'        => '7' . $faker->numerify('#########'),
                'ville'            => $faker->randomElement($villes),
                'niveau_etudes'    => $faker->randomElement($niveaux),
                'langues'          => json_encode($languesSelectionnees),
                'niveau_fr'        => $faker->randomElement($niveauxLangues),
                'niveau_en'        => in_array('Anglais', $languesSelectionnees) ? $faker->randomElement($niveauxLangues) : null,
                'niveau_es'        => in_array('Espagnol', $languesSelectionnees) ? $faker->randomElement($niveauxLangues) : null,
                'pieds_fort'       => $faker->randomElement($piedsForts),
                'numero_poste'     => $faker->numberBetween(1, 11),
                'urgence_nom'      => $faker->firstName() . ' ' . $faker->lastName(),
                'urgence_tel'      => '7' . $faker->numerify('#########'),
                'created_at'       => $faker->dateTimeBetween('-30 days', 'now'),
                'updated_at'       => now(),
            ]);
        }

        $this->command->info('100 candidats ont été créés avec succès!');
    }
}
