<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wish", name="wish_")
 */
class WishController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy([], ['dateCreated'=>'DESC']);
        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }

    /**
     * @Route("/details/{id}", name="details", requirements={"id"="\d+"})
     */
    public function details(WishRepository $wishRepository, int $id = 1): Response {
        $wish = $wishRepository->find($id);
        return $this->render("wish/details.html.twig", [
            'wish' => $wish
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response {

        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wish->setIsPublished(true);

        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()) {
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Souhait ajouté');
            return $this->redirectToRoute('wish_details', ['id'=>$wish->getId()]);
        }

        return $this->render("wish/ajout.html.twig", [
            'wishForm' => $wishForm->createView()
        ]);
    }

}
