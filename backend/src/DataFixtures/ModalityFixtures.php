<?php

namespace App\DataFixtures;

use App\Entity\Modality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ModalityFixtures extends Fixture
{
    public const MODALITY_CXR = 'modality-cxr';
    public const MODALITY_CT  = 'modality-ct';
    public const MODALITY_MRI = 'modality-mri';
    public const MODALITY_US  = 'modality-us';

    public function load(ObjectManager $manager): void
    {
        $items = [
            self::MODALITY_CXR => 'Radiographie',
            self::MODALITY_CT  => 'Scanner (CT)',
            self::MODALITY_MRI => 'IRM (MRI)',
            self::MODALITY_US  => 'Échographie (US)',
        ];

        foreach ($items as $ref => $name) {
            $m = new Modality();
            $m->setName($name);
            $manager->persist($m);

            $this->addReference($ref, $m);
        }

        $manager->flush();
    }
}
