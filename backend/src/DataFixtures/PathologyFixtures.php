<?php

namespace App\DataFixtures;

use App\Entity\Pathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class PathologyFixtures extends Fixture
{
    public const PATHO_PNEUMONIA = 'patho-pneumonia';
    public const PATHO_STROKE    = 'patho-stroke';
    public const PATHO_CHOLE     = 'patho-chole';

    // Thorax
    public const PATHO_PTX       = 'patho-pneumothorax';
    public const PATHO_PE        = 'patho-pulmonary-embolism';
    public const PATHO_OAP       = 'patho-pulmonary-edema';
    public const PATHO_COPD      = 'patho-copd-exacerbation';

    // Abdo / Digestif
    public const PATHO_APPENDI   = 'patho-appendicitis';
    public const PATHO_DIVERT    = 'patho-diverticulitis';
    public const PATHO_PANCREAT  = 'patho-pancreatitis';
    public const PATHO_BOWEL_OBS = 'patho-bowel-obstruction';

    // Uro
    public const PATHO_RENAL_COLIC = 'patho-renal-colic';
    public const PATHO_PYELONEPH   = 'patho-pyelonephritis';

    // Ostéo-articulaire / trauma
    public const PATHO_FRACT_HIP = 'patho-hip-fracture';
    public const PATHO_ANKLE_SPR = 'patho-ankle-sprain';
    public const PATHO_OA_KNEE   = 'patho-knee-osteoarthritis';

    // Neuro
    public const PATHO_SAH       = 'patho-subarachnoid-hemorrhage';

    public function load(ObjectManager $manager): void
    {
        $items = [
            // ----------------
            // Déjà existantes
            // ----------------
            [
                'ref' => self::PATHO_PNEUMONIA,
                'name' => 'Pneumonie',
                'intro' => 'Infection pulmonaire aiguë responsable d’un syndrome alvéolaire.',
                'positive' => 'Fièvre + toux/dyspnée + foyer auscultatoire et opacité alvéolaire (CXR/CT).',
                'etiologic' => 'Bactérienne (pneumocoque…), virale, aspiration, nosocomiale.',
                'evol' => 'Pleurésie, abcès, insuffisance respiratoire, sepsis.',
                'diff' => 'OAP, atélectasie, embolie pulmonaire, hémorragie alvéolaire.',
                'conclusion' => 'ATB probabiliste adaptée + évaluation de gravité, contrôle si évolution atypique.',
            ],
            [
                'ref' => self::PATHO_STROKE,
                'name' => 'AVC ischémique',
                'intro' => 'Déficit neurologique focal brutal d’origine vasculaire.',
                'positive' => 'Clinique compatible + imagerie (TDM/IRM) : signes précoces/territoire vasculaire.',
                'etiologic' => 'Athérome, cardio-embolique, lacunaire, dissection, causes rares.',
                'evol' => 'Œdème, transformation hémorragique, complications respiratoires et thromboemboliques.',
                'diff' => 'AVC hémorragique, tumeur, hypoglycémie, crise comitiale, migraine avec aura.',
                'conclusion' => 'Urgence : fenêtre thrombolyse/thrombectomie + prévention secondaire.',
            ],
            [
                'ref' => self::PATHO_CHOLE,
                'name' => 'Lithiase vésiculaire',
                'intro' => 'Présence de calculs dans la vésicule biliaire, souvent asymptomatique.',
                'positive' => 'Douleur HCD/post-prandiale + échographie : calcul(s), cône d’ombre, +/- Murphy.',
                'etiologic' => 'Calculs cholestéroliques (majoritaires) ou pigmentaires.',
                'evol' => 'Colique hépatique, cholécystite, angiocholite, pancréatite biliaire.',
                'diff' => 'Ulcère, colique néphrétique, hépatite, douleur pariétale, pancréatite non biliaire.',
                'conclusion' => 'Traitement symptomatique ; cholécystectomie si formes compliquées/récidivantes.',
            ],

            // ----------------
            // Thorax
            // ----------------
            [
                'ref' => self::PATHO_PTX,
                'name' => 'Pneumothorax',
                'intro' => 'Présence d’air dans l’espace pleural entraînant un collapsus pulmonaire partiel/total.',
                'positive' => 'Dyspnée/douleur thoracique + CXR/CT : ligne pleurale, hyperclarté, absence de trame.',
                'etiologic' => 'Spontané (primaire/secondaire), traumatique, iatrogène.',
                'evol' => 'Pneumothorax compressif, récidive, détresse respiratoire (BPCO).',
                'diff' => 'Bulle emphysémateuse géante, atélectasie, crise d’asthme, douleur pariétale.',
                'conclusion' => 'Oxygène/ponction/drain selon taille et tolérance ; urgence si compressif.',
            ],
            [
                'ref' => self::PATHO_PE,
                'name' => 'Embolie pulmonaire',
                'intro' => 'Occlusion d’une artère pulmonaire par un thrombus, le plus souvent d’origine veineuse.',
                'positive' => 'Dyspnée brutale +/- douleur pleurale, D-dimères selon contexte, angio-CT : défaut de contraste.',
                'etiologic' => 'TVP, immobilisation, cancer, chirurgie, grossesse, thrombophilie.',
                'evol' => 'Choc obstructif, infarctus pulmonaire, HTAP thromboembolique chronique.',
                'diff' => 'Pneumonie, pneumothorax, SCA, péricardite, crise d’asthme/BPCO.',
                'conclusion' => 'Anticoagulation ; reperfusion si EP à haut risque ; prévention des récidives.',
            ],
            [
                'ref' => self::PATHO_OAP,
                'name' => 'Œdème aigu du poumon (OAP)',
                'intro' => 'Inondation alvéolaire le plus souvent cardiogénique, responsable de détresse respiratoire.',
                'positive' => 'Dyspnée aiguë, râles + CXR : opacités bilatérales, redistribution vasculaire, lignes de Kerley.',
                'etiologic' => 'Insuffisance cardiaque, syndrome coronarien, troubles du rythme, surcharge hydro-sodée.',
                'evol' => 'Hypoxémie sévère, ventilation non invasive/intubation, choc cardiogénique.',
                'diff' => 'Pneumonie bilatérale, SDRA, hémorragie alvéolaire, EP.',
                'conclusion' => 'Diurétiques/vasodilatateurs/O2/VNI selon contexte + traitement de la cause.',
            ],
            [
                'ref' => self::PATHO_COPD,
                'name' => 'Exacerbation de BPCO',
                'intro' => 'Aggravation aiguë des symptômes respiratoires chez un patient BPCO.',
                'positive' => 'Dyspnée + augmentation expectoration ; CXR utile pour exclure pneumonie/OAP.',
                'etiologic' => 'Infection virale/bactérienne, pollution, non observance, EP.',
                'evol' => 'Hypercapnie, acidose respiratoire, décompensation nécessitant VNI.',
                'diff' => 'Asthme, OAP, pneumonie, EP, pneumothorax.',
                'conclusion' => 'Bronchodilatateurs + corticothérapie courte +/- ATB selon critères ; VNI si hypercapnie.',
            ],

            // ----------------
            // Abdo / Digestif
            // ----------------
            [
                'ref' => self::PATHO_APPENDI,
                'name' => 'Appendicite aiguë',
                'intro' => 'Inflammation aiguë de l’appendice, urgence fréquente.',
                'positive' => 'Douleur FID + syndrome inflammatoire ; écho/CT : appendice augmenté, infiltration graisseuse.',
                'etiologic' => 'Obstruction (fécalithe), hyperplasie lymphoïde, causes rares.',
                'evol' => 'Perforation, abcès, péritonite, plastron.',
                'diff' => 'Gastro-entérite, colique néphrétique, diverticulite droite, GYN (GEU, torsion).',
                'conclusion' => 'Antibiothérapie selon protocole + appendicectomie ou traitement médical sélectionné.',
            ],
            [
                'ref' => self::PATHO_DIVERT,
                'name' => 'Diverticulite sigmoïdienne',
                'intro' => 'Inflammation/infection d’un diverticule colique, typiquement au sigmoïde.',
                'positive' => 'Douleur FIG + fièvre ; CT : épaississement pariétal, infiltration graisseuse, diverticules.',
                'etiologic' => 'Micro-perforation diverticulaire, facteurs alimentaires et âge.',
                'evol' => 'Abcès, fistule, perforation, sténose.',
                'diff' => 'Colite, appendicite, colique néphrétique, cancer colique.',
                'conclusion' => 'ATB selon gravité ; drainage si abcès ; chirurgie si complications/récidives.',
            ],
            [
                'ref' => self::PATHO_PANCREAT,
                'name' => 'Pancréatite aiguë',
                'intro' => 'Inflammation aiguë du pancréas, d’intensité variable.',
                'positive' => 'Douleur épigastrique transfixiante + lipase élevée ; CT si forme sévère/diagnostic incertain.',
                'etiologic' => 'Biliaire, alcool, hyperTG, médicaments, causes rares.',
                'evol' => 'Nécrose, collections, infection, défaillance multiviscérale.',
                'diff' => 'Ulcère perforé, cholécystite, SCA, dissection aortique.',
                'conclusion' => 'Support (remplissage/antalgiques) + traiter la cause ; prise en charge spécialisée si sévère.',
            ],
            [
                'ref' => self::PATHO_BOWEL_OBS,
                'name' => 'Occlusion intestinale',
                'intro' => 'Interruption du transit par obstacle mécanique ou iléus.',
                'positive' => 'Douleurs + vomissements + arrêt matières/gaz ; ASP/CT : niveaux hydro-aériques, zone de transition.',
                'etiologic' => 'Brides/adhérences, hernie, tumeur, volvulus, fécalome.',
                'evol' => 'Ischémie, perforation, sepsis, déshydratation.',
                'diff' => 'Gastro-entérite, colique néphrétique, constipation sévère, douleur fonctionnelle.',
                'conclusion' => 'Réhydratation + décompression ; chirurgie si strangulation/échec.',
            ],

            // ----------------
            // Uro
            // ----------------
            [
                'ref' => self::PATHO_RENAL_COLIC,
                'name' => 'Colique néphrétique',
                'intro' => 'Douleur aiguë liée à l’obstruction des voies urinaires par calcul.',
                'positive' => 'Douleur lombaire irradiant + hématurie ; CT low-dose : calcul/urétéro-hydronéphrose.',
                'etiologic' => 'Lithiases (oxalate de calcium…), facteurs métaboliques et hydriques.',
                'evol' => 'Infection sur obstacle, insuffisance rénale obstructive, récidives.',
                'diff' => 'Appendicite, anévrysme/dissection, lombalgie, pyélonéphrite.',
                'conclusion' => 'Antalgie + hydratation adaptée ; urologie si complication (fièvre, IR, douleur rebelle).',
            ],
            [
                'ref' => self::PATHO_PYELONEPH,
                'name' => 'Pyélonéphrite aiguë',
                'intro' => 'Infection bactérienne du parenchyme rénal.',
                'positive' => 'Fièvre + douleur lombaire + BU/culture ; imagerie si forme compliquée.',
                'etiologic' => 'E. coli majoritaire ; facteurs : obstacle, reflux, diabète, grossesse.',
                'evol' => 'Abcès rénal, sepsis, choc ; emphysémateuse chez diabétique.',
                'diff' => 'Colique néphrétique, lombalgie, pneumonie basale, appendicite.',
                'conclusion' => 'ATB probabiliste puis adaptée ; drainage si obstacle/abcès.',
            ],

            // ----------------
            // Ostéo / Trauma
            // ----------------
            [
                'ref' => self::PATHO_FRACT_HIP,
                'name' => 'Fracture de l’extrémité supérieure du fémur',
                'intro' => 'Fracture du col ou de la région trochantérienne, fréquente chez le sujet âgé.',
                'positive' => 'Douleur hanche + impotence ; radio bassin/hanche, CT/IRM si radio normale et suspicion.',
                'etiologic' => 'Chute, ostéoporose, traumatisme à faible énergie.',
                'evol' => 'Complications thromboemboliques, escarres, perte d’autonomie.',
                'diff' => 'Contusion, fracture du bassin, luxation, douleur rachidienne projetée.',
                'conclusion' => 'Prise en charge orthopédique rapide + prévention complications et rééducation.',
            ],
            [
                'ref' => self::PATHO_ANKLE_SPR,
                'name' => 'Entorse de cheville',
                'intro' => 'Lésion ligamentaire de la cheville après inversion/éversion, très fréquente.',
                'positive' => 'Douleur malléolaire +/- instabilité ; radio si critères d’Ottawa.',
                'etiologic' => 'Traumatisme sportif, faux pas.',
                'evol' => 'Instabilité chronique, douleur persistante, lésion ostéochondrale.',
                'diff' => 'Fracture malléolaire, rupture tendon d’Achille, contusion.',
                'conclusion' => 'RICE/immobilisation fonctionnelle + rééducation ; avis ortho si instabilité/fracture.',
            ],
            [
                'ref' => self::PATHO_OA_KNEE,
                'name' => 'Gonarthrose (arthrose du genou)',
                'intro' => 'Dégénérescence du cartilage du genou, cause fréquente de douleur chronique.',
                'positive' => 'Douleur mécanique ; radio : pincement, ostéophytes, sclérose sous-chondrale.',
                'etiologic' => 'Âge, surcharge pondérale, axe, antécédents traumatiques.',
                'evol' => 'Limitation fonctionnelle progressive, poussées inflammatoires.',
                'diff' => 'Arthrite inflammatoire, lésion méniscale, nécrose, infection.',
                'conclusion' => 'Mesures hygiéno-diététiques + kiné/antalgiques ; chirurgie si échec et handicap.',
            ],

            // ----------------
            // Neuro (hémorragique)
            // ----------------
            [
                'ref' => self::PATHO_SAH,
                'name' => 'Hémorragie sous-arachnoïdienne (HSA)',
                'intro' => 'Saignement dans l’espace sous-arachnoïdien, souvent lié à la rupture d’un anévrysme.',
                'positive' => 'Céphalée “coup de tonnerre” ; TDM : sang sous-arachnoïdien ; angio-CT/IRM pour cause.',
                'etiologic' => 'Rupture d’anévrysme, malformation vasculaire, causes rares.',
                'evol' => 'Vasospasme, hydrocephalie, récidive hémorragique, complications neuro-réa.',
                'diff' => 'Migraine, méningite, dissection, thrombose veineuse cérébrale.',
                'conclusion' => 'Urgence neurovasculaire : sécurisation anévrysme + prévention vasospasme/complications.',
            ],
        ];

        foreach ($items as $d) {
            $p = new Pathology();
            $p->setName($d['name']);
            $p->setIntroduction($d['intro']);
            $p->setPositiveDiagnosis($d['positive']);
            $p->setEtiologicalDiagnosis($d['etiologic']);
            $p->setEvolutionComplications($d['evol']);
            $p->setDifferentialDiagnosis($d['diff']);
            $p->setConclusion($d['conclusion']);

            $manager->persist($p);
            $this->addReference($d['ref'], $p);
        }

        $manager->flush();
    }
}
