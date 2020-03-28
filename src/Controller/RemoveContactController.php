<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\UsersContactsType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RemoveContactController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    public function __construct(EntityManagerInterface $entityManager, ContactRepository $contactRepository)
    {
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/remove-contact", name="remove_contact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $userToDelete = $this->contactRepository->find($request->request->get('contact_id'));

            $this->entityManager->remove($userToDelete);
            $this->entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('remove_contact/index.html.twig', [
            'contacts' => $user->getContacts(),
        ]);
    }
}
