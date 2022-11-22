<?php

declare (strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class CompanyController extends AbstractController
{
    
    public $form;
    public $name;
    public $saveData;
    public $description;
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function loadTemplate():Response
    {
        return $this->render('company/index.html.twig', ['name'=>'Welcome']);
    }

    public function add(Request $request):Response
    {
        $this->form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('saveData', SubmitType::class, ['label' => 'Create Company'])
            ->getForm();

        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $data=$this->form->getData();
            $name=$data['name'];
            $description= $data['description'];

            $this->logger->error(
                "error saving data",
                [json_encode(['name' => $name, 'description' => $description])]
            );
            return $this->redirectToRoute('company_controller');
        }
        return $this->render('company/index.html.twig', [
            'form'=>$this->form->createView()
        ]);
    }
}
