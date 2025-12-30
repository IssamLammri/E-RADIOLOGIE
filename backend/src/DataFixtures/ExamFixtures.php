<?php

namespace App\DataFixtures;

use App\Entity\Exam;
use App\Entity\Modality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class ExamFixtures extends Fixture implements DependentFixtureInterface
{
    // Tu peux garder quelques constantes si tu en as besoin ailleurs
    public const EXAM_CXR_THORAX = 'exam-cxr-thorax';
    public const EXAM_CT_HEAD    = 'exam-ct-head';
    public const EXAM_MRI_BRAIN  = 'exam-mri-brain';
    public const EXAM_US_ABDO    = 'exam-us-abdo';

    public function load(ObjectManager $manager): void
    {
        $exams = [
            // --------------------
            // CXR (Radiographie)
            // --------------------
            [
                'ref' => self::EXAM_CXR_THORAX,
                'name' => 'Radiographie Thorax (Face)',
                'description' => 'Radiographie standard du thorax de face.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-thorax-profil',
                'name' => 'Radiographie Thorax (Profil)',
                'description' => 'Radiographie du thorax en incidence de profil.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-abdomen-sans-prepa',
                'name' => 'ASP (Abdomen sans préparation)',
                'description' => 'Radiographie de l’abdomen, sans préparation.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-rachis-cervical',
                'name' => 'Radiographie Rachis Cervical',
                'description' => 'Clichés cervicaux (face/profil) selon protocole.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-rachis-lombaire',
                'name' => 'Radiographie Rachis Lombaire',
                'description' => 'Clichés lombaires face/profil +/- obliques.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-bassin',
                'name' => 'Radiographie Bassin',
                'description' => 'Radiographie du bassin de face.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-epaule',
                'name' => 'Radiographie Épaule',
                'description' => 'Incidences standards de l’épaule.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-poignet',
                'name' => 'Radiographie Poignet',
                'description' => 'Incidences standards du poignet.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-main',
                'name' => 'Radiographie Main',
                'description' => 'Incidences standards de la main.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-cheville',
                'name' => 'Radiographie Cheville',
                'description' => 'Incidences standards de la cheville.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],
            [
                'ref' => 'exam-cxr-pied',
                'name' => 'Radiographie Pied',
                'description' => 'Incidences standards du pied.',
                'modalityRef' => ModalityFixtures::MODALITY_CXR,
            ],

            // --------------------
            // CT (Scanner)
            // --------------------
            [
                'ref' => self::EXAM_CT_HEAD,
                'name' => 'Scanner Crâne sans injection',
                'description' => 'TDM cérébrale sans injection.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-crane-angio',
                'name' => 'Angio-Scanner cérébral',
                'description' => 'TDM cérébrale avec injection (angio) selon indication.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-sinus',
                'name' => 'Scanner des Sinus',
                'description' => 'TDM des sinus (coupes fines) sans injection.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-thorax',
                'name' => 'Scanner Thorax',
                'description' => 'TDM thoracique avec ou sans injection selon indication.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-angio-pulmonaire',
                'name' => 'Angio-Scanner Pulmonaire',
                'description' => 'TDM thoracique injectée pour recherche d’embolie pulmonaire.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-abdomen-pelvis',
                'name' => 'Scanner Abdomen-Pelvis',
                'description' => 'TDM abdomino-pelvienne avec injection selon indication.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-uro',
                'name' => 'Uro-Scanner',
                'description' => 'TDM urographique (temps sans et avec injection).',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-colo',
                'name' => 'Colo-Scanner',
                'description' => 'TDM colique (colonographie) selon protocole.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-rachis-cervical',
                'name' => 'Scanner Rachis Cervical',
                'description' => 'TDM cervicale (trauma/dégénératif) sans injection.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],
            [
                'ref' => 'exam-ct-rachis-lombaire',
                'name' => 'Scanner Rachis Lombaire',
                'description' => 'TDM lombaire sans injection.',
                'modalityRef' => ModalityFixtures::MODALITY_CT,
            ],

            // --------------------
            // MRI (IRM)
            // --------------------
            [
                'ref' => self::EXAM_MRI_BRAIN,
                'name' => 'IRM Cérébrale',
                'description' => 'IRM cérébrale (T1/T2/FLAIR/DWI) +/- injection.',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-hypophyse',
                'name' => 'IRM Hypophyse',
                'description' => 'IRM hypophysaire (coupes fines) +/- injection.',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-orbite',
                'name' => 'IRM Orbites',
                'description' => 'IRM des orbites selon protocole.',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-rachis-cervical',
                'name' => 'IRM Rachis Cervical',
                'description' => 'IRM cervicale (T1/T2/STIR) selon indication.',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-rachis-lombaire',
                'name' => 'IRM Rachis Lombaire',
                'description' => 'IRM lombaire (T1/T2/STIR) selon indication.',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-genou',
                'name' => 'IRM Genou',
                'description' => 'IRM du genou (ménisques/ligaments/cartilage).',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-epaule',
                'name' => 'IRM Épaule',
                'description' => 'IRM de l’épaule (coiffe des rotateurs) +/- arthro.',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-bassin',
                'name' => 'IRM Pelvienne',
                'description' => 'IRM pelvienne selon indication (gynéco/urologique).',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],
            [
                'ref' => 'exam-mri-prostate',
                'name' => 'IRM Prostate (mpMRI)',
                'description' => 'IRM prostatique multiparamétrique (T2/DWI/DCE).',
                'modalityRef' => ModalityFixtures::MODALITY_MRI,
            ],

            // --------------------
            // US (Échographie)
            // --------------------
            [
                'ref' => self::EXAM_US_ABDO,
                'name' => 'Échographie Abdominale',
                'description' => 'Échographie foie, voies biliaires, pancréas, reins.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-renale',
                'name' => 'Échographie Rénale',
                'description' => 'Échographie reins et voies urinaires.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-vesicule-biliaire',
                'name' => 'Échographie Vésicule / Voies biliaires',
                'description' => 'Échographie vésicule biliaire et voies biliaires.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-thyroide',
                'name' => 'Échographie Thyroïde',
                'description' => 'Échographie thyroïdienne et ganglionnaire cervicale.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-doppler-veineux-mi',
                'name' => 'Doppler Veineux Membres Inférieurs',
                'description' => 'Écho-Doppler veineux MI (TVP) selon indication.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-doppler-arteriel-mi',
                'name' => 'Doppler Artériel Membres Inférieurs',
                'description' => 'Écho-Doppler artériel MI selon indication.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-carotides',
                'name' => 'Doppler des Carotides',
                'description' => 'Écho-Doppler carotidien et vertébral.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-gyneco-pelvienne',
                'name' => 'Échographie Pelvienne (Gynécologique)',
                'description' => 'Échographie pelvienne par voie sus-pubienne +/- endovaginale.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-obstetricale-t1',
                'name' => 'Échographie Obstétricale T1',
                'description' => 'Échographie de datation / 1er trimestre selon protocole.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-obstetricale-t2',
                'name' => 'Échographie Obstétricale T2',
                'description' => 'Échographie morphologique / 2e trimestre selon protocole.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
            [
                'ref' => 'exam-us-obstetricale-t3',
                'name' => 'Échographie Obstétricale T3',
                'description' => 'Échographie de croissance / 3e trimestre selon protocole.',
                'modalityRef' => ModalityFixtures::MODALITY_US,
            ],
        ];

        foreach ($exams as $data) {
            $e = new Exam();
            $e->setName($data['name']);
            $e->setDescription($data['description']);
            $e->setModality($this->getReference($data['modalityRef'], Modality::class));
            $manager->persist($e);

            $this->addReference($data['ref'], $e);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ModalityFixtures::class];
    }
}
