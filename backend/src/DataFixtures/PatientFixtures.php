<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class PatientFixtures extends Fixture
{
    public const PATIENT_1 = 'patient-1';
    public const PATIENT_2 = 'patient-2';
    public const PATIENT_3 = 'patient-3';

    public function load(ObjectManager $manager): void
    {
        // Pour avoir du "random" stable d’un run à l’autre (pratique en dev)
        mt_srand(20251230);

        $basePatients = [
            [
                'ref' => self::PATIENT_1,
                'age' => 42,
                'gender' => 'Homme',
                'history' => 'HTA, tabagisme (10 PA).',
            ],
            [
                'ref' => self::PATIENT_2,
                'age' => 29,
                'gender' => 'Femme',
                'history' => 'Aucun antécédent notable.',
            ],
            [
                'ref' => self::PATIENT_3,
                'age' => 66,
                'gender' => 'Homme',
                'history' => 'Diabète type 2, dyslipidémie.',
            ],
        ];

        // Catalogue d'antécédents pour générer du contenu varié
        $historyPool = [
            'Aucun antécédent notable',
            'HTA',
            'Diabète type 2',
            'Dyslipidémie',
            'Tabagisme (5 PA)',
            'Tabagisme (15 PA)',
            'Obésité (IMC > 30)',
            'Asthme',
            'BPCO',
            'Hypothyroïdie',
            'Insuffisance rénale chronique (stade 3)',
            'Fibrillation auriculaire',
            'Coronaropathie',
            'AVC ischémique ancien',
            'Apnée du sommeil',
            'Allergie: pénicilline',
            'Allergie: AINS',
            'Reflux gastro-œsophagien',
            'Antécédent de phlébite/EP',
            'Anxiété / troubles du sommeil',
        ];

        $genders = ['Homme', 'Femme'];

        // On garde tes 3 patients + on en ajoute d'autres
        $patientsData = $basePatients;

        // Ajout de patients supplémentaires (ex: 25)
        for ($i = 4; $i <= 25; $i++) {
            $age = mt_rand(18, 92);
            $gender = $genders[mt_rand(0, count($genders) - 1)];

            // Génère 0 à 4 antécédents, avec un peu de cohérence (plus âgé => + probable)
            $maxItems = $age >= 65 ? 4 : ($age >= 40 ? 3 : 2);
            $count = mt_rand(0, $maxItems);

            $history = $this->pickHistory($historyPool, $count);

            // Si aucun item, on met "Aucun antécédent notable."
            $historyText = $history === []
                ? 'Aucun antécédent notable.'
                : rtrim(implode(', ', $history), '.') . '.';

            $patientsData[] = [
                'ref' => sprintf('patient-%d', $i),
                'age' => $age,
                'gender' => $gender,
                'history' => $historyText,
            ];
        }

        foreach ($patientsData as $data) {
            $p = new Patient();
            $p->setAge($data['age']);
            $p->setGender($data['gender']);
            $p->setHistory($data['history']);

            $manager->persist($p);

            // Référence pour pouvoir la réutiliser dans d'autres fixtures
            $this->addReference($data['ref'], $p);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    private function pickHistory(array $pool, int $count): array
    {
        if ($count <= 0) {
            return [];
        }

        // On évite "Aucun antécédent notable" si on a déjà décidé d'ajouter des items
        $filteredPool = array_values(array_filter(
            $pool,
            static fn (string $h) => stripos($h, 'Aucun antécédent') === false
        ));

        shuffle($filteredPool);

        // Dédup + limite
        return array_slice(array_unique($filteredPool), 0, $count);
    }
}
