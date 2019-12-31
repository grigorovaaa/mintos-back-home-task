<?php
namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractFOSRestController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RegistrationController constructor.
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }
    /**
     * @Rest\Post("/registration", name="registration")
     * @param Request $request
     * @return View
     */
    public function actionRegistration(Request $request)
    {
        $email = $request->get('email');
        if (is_null($email)) {
            return $this->view([
                'message' => 'email cannot be null'
            ], Response::HTTP_CONFLICT);
        }
        $password = $request->get('password');
        if (is_null($password)) {
            return $this->view([
                'message' => 'password cannot be null'
            ], Response::HTTP_CONFLICT);
        }

        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);
        if (!is_null($user)) {
            return $this->view([
                'message' => 'User already exists'
            ], Response::HTTP_CONFLICT);
        }
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $password)
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->view($user, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public']));
    }

}