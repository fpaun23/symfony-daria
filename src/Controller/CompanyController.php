<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * class for company controller
 */
class CompanyController extends AbstractController
{
    private $logger;


    /**
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * loadTemplate
     *
     * @return Response
     */
    public function loadTemplate(): Response
    {
        return $this->render('company/index.html.twig', ['name' => 'Welcome']);
    }

    /**
     * add fields for the form and the submitted data in varlog
     *
     * @param  mixed $request
     * @return Response
     */
    // public function add(Request $request): Response
    // {
    //     $this->form = $this->createFormBuilder()
    //         ->add('name', TextType::class)
    //         ->add('description', TextType::class)
    //         ->add('email', EmailType::class)
    //         ->add('saveData', SubmitType::class, ['label' => 'Create Company'])
    //         ->getForm();

    //     $this->form->handleRequest($request);

    //     if ($this->form->isSubmitted() && $this->form->isValid()) {
    //         $data = $this->form->getData();
    //         $name = $data['name'];
    //         $description = $data['description'];
    //         $email = $data['email'];


    //         $this->logger->error(
    //             "Data is saved!",
    //             [json_encode(['name' => $name, 'description' => $description , 'email' => $email])]
    //         );
    //         return $this->redirectToRoute('company_controller');
    //     }
    //     return $this->render('company/index.html.twig', [
    //         'form' => $this->form->createView()
    //     ]);
    // }
    /**
     * adds Company to the db
     *
     * @param  mixed $doctrine
     * @return Response
     */
    public function addCompany(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $company = new Company();
        $company->setName('ApacheNe2');
        $entityManager->persist($company);
        $entityManager->flush();

        return new Response('Saved new company with id ' . $company->getId());
    }
    /**
     * updates company name with id
     *
     * @param  mixed $doctrine
     * @param  mixed $id
     * @return Response
     */
    public function updateCompany(ManagerRegistry $doctrine, int $id): Response
    {
        $updateId = null;
        $entityManager = $doctrine->getManager();
        $company = $entityManager->getRepository(Company::class)->find($id);
        if (!empty($company)) {
            $updateId = $company->getId();
            $company->setName('.NetDev');
            $entityManager->flush();
        }
        return new JsonResponse([
            'update' => !empty($updateId),
            'updateId' => $updateId
        ]);
    }
    public function deleteCompany(ManagerRegistry $doctrine, int $id): Response
    {
        $deletedId = null;
        $entityManager = $doctrine->getManager();
        $company = $entityManager->getRepository(Company::class)->find($id);
        if (!empty($company)) {
            $deletedId = $company->getId();
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return new JsonResponse([
            'detleted' => !empty($deletedId),
            'deleteId' => $deletedId
        ]);
    }
    /**
     * lists the companies from the db
     *
     * @param  mixed $doctrine
     * @param  mixed $id
     * @return Response
     */
    public function listCompany(ManagerRegistry $doctrine): Response
    {
        $company = $doctrine->getRepository(Company::class)->findAll();
        //var_dump($company);die;
        return new Response(json_encode($company));
    }
}
