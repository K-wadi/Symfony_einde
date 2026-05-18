<?php

namespace App\Controller\Admin;

use App\Entity\Employee;
use App\Entity\User;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/medewerkers')]
#[IsGranted('ROLE_OWNER')]
// Admin: medewerkers en loginaccounts beheren.
class EmployeeController extends AbstractController
{
    #[Route('', name: 'kapsalon_admin_employees')]
    public function index(EmployeeRepository $repository): Response
    {
        return $this->render('kapsalon/admin/employee/index.html.twig', [
            'employees' => $repository->findAll(),
        ]);
    }

    #[Route('/nieuw', name: 'kapsalon_admin_employee_new')]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setEmail($form->get('email')->getData());
            $user->setFirstName($employee->getName());
            $user->setRoles(['ROLE_EMPLOYEE']);
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
            $employee->setUser($user);
            $em->persist($user);
            $em->persist($employee);
            $em->flush();
            $this->addFlash('success', 'Medewerker toegevoegd.');

            return $this->redirectToRoute('kapsalon_admin_employees');
        }

        return $this->render('kapsalon/admin/employee/form.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/verwijderen', name: 'kapsalon_admin_employee_delete', methods: ['POST'])]
    public function delete(Employee $employee, EntityManagerInterface $em): Response
    {
        if ($employee->getUser()) {
            $em->remove($employee->getUser());
        }
        $em->remove($employee);
        $em->flush();
        $this->addFlash('success', 'Medewerker verwijderd.');

        return $this->redirectToRoute('kapsalon_admin_employees');
    }
}
