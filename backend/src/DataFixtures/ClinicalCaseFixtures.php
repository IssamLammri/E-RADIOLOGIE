<?php

namespace App\DataFixtures;

use App\Entity\ClinicalCase;
use App\Entity\Exam;
use App\Entity\Pathology;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

final class ClinicalCaseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Random stable
        mt_srand(20251230);

        $cases = [
            // -----------------------
            // Tes 3 cas initiaux
            // -----------------------
            [
                'patientRef' => PatientFixtures::PATIENT_1,
                'examRef' => ExamFixtures::EXAM_CXR_THORAX,
                'pathologyRef' => PathologyFixtures::PATHO_PNEUMONIA,
                'symptoms' => 'Fièvre, toux productive, douleur thoracique.',
                'imageComment' => 'Opacité alvéolaire basale droite avec bronchogramme aérique.',
                'conclusion' => 'Aspect compatible avec une pneumonie lobaire.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_3,
                'examRef' => ExamFixtures::EXAM_CT_HEAD,
                'pathologyRef' => PathologyFixtures::PATHO_STROKE,
                'symptoms' => 'Hémiparésie brutale, troubles du langage.',
                'imageComment' => 'Hypodensité cortico-sous-corticale du territoire sylvien gauche (signes précoces).',
                'conclusion' => 'AVC ischémique probable, à corréler à l’heure de début des symptômes.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_2,
                'examRef' => ExamFixtures::EXAM_US_ABDO,
                'pathologyRef' => PathologyFixtures::PATHO_CHOLE,
                'symptoms' => 'Douleur HCD post-prandiale, nausées.',
                'imageComment' => 'Calculs vésiculaires mobiles avec cône d’ombre postérieur, paroi non épaissie.',
                'conclusion' => 'Lithiase vésiculaire sans signe de complication.',
                'images' => null,
            ],

            // -----------------------
            // Thorax - CXR
            // -----------------------
            [
                'patientRef' => PatientFixtures::PATIENT_1,
                'examRef' => 'exam-cxr-thorax-profil',
                'pathologyRef' => PathologyFixtures::PATHO_PNEUMONIA,
                'symptoms' => 'Fièvre, dyspnée, toux.',
                'imageComment' => 'Foyer alvéolaire rétro-cardiaque mieux visible sur le profil.',
                'conclusion' => 'Pneumonie probable. Contrôle radiographique recommandé selon évolution.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_3,
                'examRef' => ExamFixtures::EXAM_CXR_THORAX,
                'pathologyRef' => PathologyFixtures::PATHO_OAP,
                'symptoms' => 'Dyspnée aiguë, orthopnée, expectoration mousseuse.',
                'imageComment' => 'Opacités péri-hilaires bilatérales floues + redistribution vasculaire.',
                'conclusion' => 'Aspect évocateur d’un OAP cardiogénique.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_1,
                'examRef' => ExamFixtures::EXAM_CXR_THORAX,
                'pathologyRef' => PathologyFixtures::PATHO_PTX,
                'symptoms' => 'Douleur thoracique brutale, dyspnée.',
                'imageComment' => 'Ligne pleurale droite avec absence de trame vasculaire périphérique.',
                'conclusion' => 'Pneumothorax droit non compressif (à quantifier).',
                'images' => null,
            ],

            // -----------------------
            // Thorax - CT
            // -----------------------
            [
                'patientRef' => PatientFixtures::PATIENT_3,
                'examRef' => 'exam-ct-angio-pulmonaire',
                'pathologyRef' => PathologyFixtures::PATHO_PE,
                'symptoms' => 'Dyspnée brutale, douleur thoracique latéralisée, tachycardie.',
                'imageComment' => 'Défaut de rehaussement endoluminal d’une artère lobaire (signe direct).',
                'conclusion' => 'Embolie pulmonaire confirmée au scanner.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_1,
                'examRef' => 'exam-ct-thorax',
                'pathologyRef' => PathologyFixtures::PATHO_PNEUMONIA,
                'symptoms' => 'Fièvre persistante malgré ATB, dyspnée.',
                'imageComment' => 'Condensation segmentaire + bronchogramme aérique, absence d’épanchement significatif.',
                'conclusion' => 'Pneumonie ; pas d’argument scanographique pour complication.',
                'images' => null,
            ],

            // -----------------------
            // Neuro - CT / IRM
            // -----------------------
            [
                'patientRef' => PatientFixtures::PATIENT_3,
                'examRef' => 'exam-ct-crane-angio',
                'pathologyRef' => PathologyFixtures::PATHO_SAH,
                'symptoms' => 'Céphalée brutale intense, vomissements, photophobie.',
                'imageComment' => 'Hyperdensités des citernes de la base ; angio : suspicion lésion anévrysmale.',
                'conclusion' => 'HSA probable. Avis neurovasculaire urgent.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_2,
                'examRef' => ExamFixtures::EXAM_MRI_BRAIN,
                'pathologyRef' => PathologyFixtures::PATHO_STROKE,
                'symptoms' => 'Trouble de la parole transitoire, faiblesse du bras droit.',
                'imageComment' => 'Hypersignal DWI avec restriction de diffusion compatible avec infarctus récent.',
                'conclusion' => 'AVC ischémique récent confirmé en IRM.',
                'images' => null,
            ],

            // -----------------------
            // Abdo - US / CT
            // -----------------------
            [
                'patientRef' => PatientFixtures::PATIENT_2,
                'examRef' => ExamFixtures::EXAM_US_ABDO,
                'pathologyRef' => PathologyFixtures::PATHO_APPENDI,
                'symptoms' => 'Douleur FID, fièvre modérée, nausées.',
                'imageComment' => 'Structure tubulaire non compressible en FID, diamètre augmenté.',
                'conclusion' => 'Appendicite aiguë probable (à compléter par CT si doute clinique).',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_1,
                'examRef' => 'exam-ct-abdomen-pelvis',
                'pathologyRef' => PathologyFixtures::PATHO_DIVERT,
                'symptoms' => 'Douleur FIG, fièvre, défense localisée.',
                'imageComment' => 'Épaississement sigmoïdien + infiltration graisseuse ; microbulles péri-coliques.',
                'conclusion' => 'Diverticulite sigmoïdienne (forme compliquée légère possible).',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_3,
                'examRef' => 'exam-ct-abdomen-pelvis',
                'pathologyRef' => PathologyFixtures::PATHO_BOWEL_OBS,
                'symptoms' => 'Vomissements, distension, arrêt matières et gaz.',
                'imageComment' => 'Distension grêlique + zone de transition ; pas de signe évident d’ischémie.',
                'conclusion' => 'Occlusion intestinale mécanique probable. Surveillance signes de souffrance.',
                'images' => null,
            ],

            // -----------------------
            // Uro - CT
            // -----------------------
            [
                'patientRef' => PatientFixtures::PATIENT_1,
                'examRef' => 'exam-ct-uro',
                'pathologyRef' => PathologyFixtures::PATHO_RENAL_COLIC,
                'symptoms' => 'Douleur lombaire intense irradiant vers l’aine, hématurie.',
                'imageComment' => 'Calcul urétéral distal avec urétéro-hydronéphrose en amont.',
                'conclusion' => 'Colique néphrétique sur calcul obstructif.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_2,
                'examRef' => 'exam-ct-abdomen-pelvis',
                'pathologyRef' => PathologyFixtures::PATHO_PYELONEPH,
                'symptoms' => 'Fièvre, frissons, douleur lombaire, brûlures mictionnelles.',
                'imageComment' => 'Rehaussement hétérogène en “stries” du parenchyme rénal (forme non compliquée).',
                'conclusion' => 'Arguments scanographiques compatibles avec une pyélonéphrite.',
                'images' => null,
            ],

            // -----------------------
            // Ostéo - X-ray
            // -----------------------
            [
                'patientRef' => PatientFixtures::PATIENT_3,
                'examRef' => 'exam-cxr-bassin',
                'pathologyRef' => PathologyFixtures::PATHO_FRACT_HIP,
                'symptoms' => 'Chute, douleur hanche, impotence fonctionnelle.',
                'imageComment' => 'Interruption corticale au col fémoral / région trochantérienne (selon cliché).',
                'conclusion' => 'Fracture de l’extrémité supérieure du fémur probable.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_2,
                'examRef' => 'exam-cxr-cheville',
                'pathologyRef' => PathologyFixtures::PATHO_ANKLE_SPR,
                'symptoms' => 'Torsion cheville, douleur malléolaire externe, oedème.',
                'imageComment' => 'Pas d’anomalie osseuse évidente ; parties molles tuméfiées.',
                'conclusion' => 'Pas d’argument radiologique pour fracture. Entorse probable.',
                'images' => null,
            ],
            [
                'patientRef' => PatientFixtures::PATIENT_1,
                'examRef' => 'exam-cxr-poignet',
                'pathologyRef' => PathologyFixtures::PATHO_ANKLE_SPR,
                'symptoms' => 'Chute sur la main, douleur du poignet (cas “piège”).',
                'imageComment' => 'Radiographie sans lésion évidente ; douleur persistante clinique.',
                'conclusion' => 'Radiographie normale : discuter clichés complémentaires/contrôle si suspicion clinique.',
                'images' => null,
            ],
        ];

        // Optionnel: générer quelques cas supplémentaires "cohérents" automatiquement
        $autoCases = $this->generateAutoCases(10);
        $cases = array_merge($cases, $autoCases);

        foreach ($cases as $d) {
            $cc = new ClinicalCase();
            $cc->setPatient($this->getReference($d['patientRef'], Patient::class));
            $cc->setExam($this->getReference($d['examRef'], Exam::class));
            $cc->setPathology($this->getReference($d['pathologyRef'], Pathology::class));
            $cc->setSymptoms($d['symptoms']);
            $cc->setImageComment($d['imageComment']);
            $cc->setConclusion($d['conclusion']);

            if (!empty($d['images']) && method_exists($cc, 'setImages')) {
                $cc->setImages($d['images']);
            }

            $manager->persist($cc);
        }

        $manager->flush();
    }

    /**
     * Génère des cas plausibles en respectant des couples exam/pathologie compatibles.
     *
     * @return array<int, array{patientRef:string, examRef:string, pathologyRef:string, symptoms:string, imageComment:string, conclusion:string, images: ?string}>
     */
    private function generateAutoCases(int $count): array
    {
        $patientRefs = [
            PatientFixtures::PATIENT_1,
            PatientFixtures::PATIENT_2,
            PatientFixtures::PATIENT_3,
            // Si tu as ajouté patient-4..patient-25, tu peux aussi les mettre ici :
            'patient-4','patient-5','patient-6','patient-7','patient-8','patient-9','patient-10',
        ];

        // Mapping "cohérent" : examRef => [pathologies possibles + templates]
        $map = [
            ExamFixtures::EXAM_CXR_THORAX => [
                [
                    'patho' => PathologyFixtures::PATHO_PNEUMONIA,
                    'symptoms' => 'Fièvre, toux, douleur thoracique.',
                    'comment' => 'Opacité alvéolaire focale, possible bronchogramme.',
                    'conclusion' => 'Aspect compatible avec une pneumonie.',
                ],
                [
                    'patho' => PathologyFixtures::PATHO_OAP,
                    'symptoms' => 'Dyspnée aiguë, orthopnée.',
                    'comment' => 'Opacités bilatérales péri-hilaires et surcharge vasculaire.',
                    'conclusion' => 'Aspect en faveur d’un OAP.',
                ],
                [
                    'patho' => PathologyFixtures::PATHO_PTX,
                    'symptoms' => 'Douleur thoracique brutale, dyspnée.',
                    'comment' => 'Ligne pleurale avec absence de trame vasculaire périphérique.',
                    'conclusion' => 'Pneumothorax probable (à quantifier).',
                ],
            ],
            'exam-ct-angio-pulmonaire' => [
                [
                    'patho' => PathologyFixtures::PATHO_PE,
                    'symptoms' => 'Dyspnée brutale, douleur pleurale, tachycardie.',
                    'comment' => 'Défaut de rehaussement endoluminal d’une artère pulmonaire.',
                    'conclusion' => 'Embolie pulmonaire confirmée.',
                ],
            ],
            ExamFixtures::EXAM_CT_HEAD => [
                [
                    'patho' => PathologyFixtures::PATHO_STROKE,
                    'symptoms' => 'Déficit focal d’installation brutale.',
                    'comment' => 'Hypodensité territoriale / signes précoces selon délai.',
                    'conclusion' => 'AVC ischémique probable, à corréler au timing.',
                ],
            ],
            ExamFixtures::EXAM_MRI_BRAIN => [
                [
                    'patho' => PathologyFixtures::PATHO_STROKE,
                    'symptoms' => 'Troubles neuro focaux, début récent.',
                    'comment' => 'Restriction de diffusion en faveur d’un infarctus récent.',
                    'conclusion' => 'AVC ischémique récent.',
                ],
            ],
            ExamFixtures::EXAM_US_ABDO => [
                [
                    'patho' => PathologyFixtures::PATHO_CHOLE,
                    'symptoms' => 'Douleur HCD post-prandiale, nausées.',
                    'comment' => 'Calcul(s) vésiculaire(s) avec cône d’ombre, mobilité conservée.',
                    'conclusion' => 'Lithiase vésiculaire non compliquée.',
                ],
            ],
            'exam-ct-abdomen-pelvis' => [
                [
                    'patho' => PathologyFixtures::PATHO_DIVERT,
                    'symptoms' => 'Douleur FIG, fièvre, syndrome inflammatoire.',
                    'comment' => 'Épaississement sigmoïdien et infiltration graisseuse.',
                    'conclusion' => 'Diverticulite sigmoïdienne.',
                ],
                [
                    'patho' => PathologyFixtures::PATHO_BOWEL_OBS,
                    'symptoms' => 'Distension, vomissements, arrêt du transit.',
                    'comment' => 'Distension en amont et zone de transition.',
                    'conclusion' => 'Occlusion intestinale probable.',
                ],
                [
                    'patho' => PathologyFixtures::PATHO_PYELONEPH,
                    'symptoms' => 'Fièvre, douleur lombaire, signes urinaires.',
                    'comment' => 'Rehaussement hétérogène du rein (stries) +/- infiltration.',
                    'conclusion' => 'Pyélonéphrite probable.',
                ],
            ],
            'exam-ct-uro' => [
                [
                    'patho' => PathologyFixtures::PATHO_RENAL_COLIC,
                    'symptoms' => 'Douleur lombaire aiguë, hématurie.',
                    'comment' => 'Calcul urétéral avec dilatation en amont.',
                    'conclusion' => 'Colique néphrétique obstructive.',
                ],
            ],
        ];

        $examRefs = array_keys($map);
        $out = [];

        for ($i = 0; $i < $count; $i++) {
            $patientRef = $patientRefs[mt_rand(0, count($patientRefs) - 1)];
            $examRef = $examRefs[mt_rand(0, count($examRefs) - 1)];

            $templates = $map[$examRef];
            $t = $templates[mt_rand(0, count($templates) - 1)];

            $out[] = [
                'patientRef' => $patientRef,
                'examRef' => $examRef,
                'pathologyRef' => $t['patho'],
                'symptoms' => $t['symptoms'],
                'imageComment' => $t['comment'],
                'conclusion' => $t['conclusion'],
                'images' => null,
            ];
        }

        return $out;
    }

    public function getDependencies(): array
    {
        return [
            PatientFixtures::class,
            ModalityFixtures::class,
            ExamFixtures::class,
            PathologyFixtures::class,
        ];
    }
}
