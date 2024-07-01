<?php

namespace App\Controller;

use DateTime;
use App\Entity\DownloadedFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilesController extends AbstractController
{
    #[Route('/', name: 'app_files')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FilesController.php',
        ]);
        
        
    }

    #[Route('/api/files/{downloadedFile}', name:'files.get', methods:['GET'])]
    public function getFile(DownloadedFile $downloadedFile, UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer){
        $location = $downloadedFile->getPublicPath() . '/' . $downloadedFile->getRealPath();
        $location = $urlGenerator->generate("app_files", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $location = $location . str_replace("/public/", "", $downloadedFile->getPublicPath() . '/' . $downloadedFile->getRealPath());
          return $downloadedFile ? 
            new JsonResponse($serializer->serialize($downloadedFile, 'json', []), Response::HTTP_OK, ["Location" => $location], true)
            : new JsonResponse(null, Response::HTTP_NOT_FOUND);

    }

    #[Route('/api/files', name:'files.create', methods:[ 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer){
        // $downloadedFile = new DownloadedFile();
        // $files = $request->files->get('file');
        // $downloadedFile->setFile($files);
        // $downloadedFile->setMimeType($files->getClientMimeType());
        // $downloadedFile->setRealName($files->getClientOriginalName());
        // $downloadedFile->setPublicPath('files/songs');
        // $downloadedFile->setUpdatedAt(new DateTime());
        // $downloadedFile->setCreatedAt(new DateTime());
        // $downloadedFile->setStatus("on");
        // // $downloadedFile->setFileSize(0);

        // $entityManager->persist($downloadedFile);
        // $entityManager->flush();
        // $jsonFile = $serializer->serialize($downloadedFile, 'json');
        // $location = $urlGenerator->generate('files.get' , ["downloadedFile" => $downloadedFile->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        // return new JsonResponse($jsonFile, Response::HTTP_CREATED, ['Location' => $location], true);
        return new JsonResponse(["message" => "Nice Try ;)"]);
    }
}
