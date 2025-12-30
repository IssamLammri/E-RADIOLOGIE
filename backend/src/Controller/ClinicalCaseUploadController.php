<?php

namespace App\Controller;

use App\Entity\ClinicalCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ClinicalCaseUploadController
{
    #[Route('/api/clinical_cases/{id}/image', name: 'clinical_case_upload_image', methods: ['POST'])]
    public function __invoke(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        KernelInterface $kernel
    ): JsonResponse {
        $case = $em->getRepository(ClinicalCase::class)->find($id);

        if (!$case) {
            return new JsonResponse(['message' => 'ClinicalCase not found'], 404);
        }

        /** @var UploadedFile|null $file */
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['message' => 'No file uploaded (field name must be "file")'], 400);
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowed, true)) {
            return new JsonResponse(['message' => 'Invalid file type'], 422);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $slugger->slug($originalName)->lower();
        $newFilename = $safeName . '-' . uniqid('', true) . '.' . ($file->guessExtension() ?: 'jpg');

        $uploadDir = $kernel->getProjectDir() . '/public/uploads/clinical-cases';

        // Crée le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $file->move($uploadDir, $newFilename);

        $publicPath = '/uploads/clinical-cases/' . $newFilename;

        // IMPORTANT : ton champ est "images" (string)
        $case->setImages($publicPath);

        $em->flush();

        return new JsonResponse([
            'id' => $case->getId(),
            'imageUrl' => $publicPath,
        ], 200);
    }
}
