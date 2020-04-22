<?php
// src/Controller/RegistrationController.php
namespace App\Controller\Auth;

use App\Entity\Auth\Grade;
use App\Form\Auth\UserType;
use App\Entity\Auth\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationController extends AbstractController
{

    /**
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    private $created_at;

    public function __construct()
    {
        try {
            $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        } catch (\Exception $e) {
        }
        $this->created_at = $date->format('Y-m-d H:i:s');
    }
    /**
     * @Route("/login/new", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Compte crée avec succés. Veuillez-vous connecter');

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);
            // Par défaut l'utilisateur a un role inscrit Grade  2
            //On récupére le numero du role utilisateur (id 2)
            $grade = $this->getDoctrine()->getRepository(Grade::class)->find(2);
            $user->setGrade( $grade);
            // Ajout de la date d'inscription
            $user->setRegisteredAt(new \DateTime());

            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }
}