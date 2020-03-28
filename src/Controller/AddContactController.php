<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddContactController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/add-contact", name="add_contact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $newContact = new Contact();
        $newContact->setUser($user);

        $form = $this->createForm(ContactType::class, $newContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($newContact);
            $this->entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('add_contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
