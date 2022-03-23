<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="app_api", methods={"GET"})
     */
    public function index( UserRepository $repo,SerializerInterface $serializer ): Response
    {
        $user=$repo->findAll();
        $resultat=$serializer->serialize($user, 'json' );
        return new JsonResponse($resultat,200,[],true);
    }

     /**
     * @Route("/api/{id} ", name="app_api_show", methods={"GET"})
     */
    public function show( UserRepository $repo,SerializerInterface $serializer,int $id): Response
    {
        $user=$repo->find($id);
        $resultat=$serializer->serialize( $user, 'json', ['group'=>['listUserSimple']] );

        return new JsonResponse($resultat,200,[],true);
    }

     /**
     * @Route("/api", name="app_api_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data=$request->getcontent();

        $user=$serializer->deserialize($data, User::class, 'json');
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse("le user a bien ete cree", Response::HTTP_CREATED,[],true);
    }

     /**
     * @Route("/api/{id}", name="app_api_update", methods={"PUT"})
     */
    public function edit(user $user, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data=$request->getcontent();

        $serializer->deserialize($data, User::class, 'json',['object_to_populate'=>$user]);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse("le user a bien ete modifier", Response::HTTP_OK,[],true);
    }


     /**
     * @Route("/api/w{id}", name="app_api_delete", methods={"DELETE"})
     */
    public function delete(user $user, EntityManagerInterface $entityManager): Response
    {
       
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse("le user a bien ete suprimer", Response::HTTP_OK,[],true);
    }
}
