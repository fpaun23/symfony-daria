<?php

declare (strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

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
    public function loadTemplate():Response
    {
        return $this->render('company/index.html.twig', ['name'=>'Welcome']);
    }
    
    /**
     * add fields for the form and the submitted data in varlog
     *
     * @param  mixed $request
     * @return Response
     */
    public function add(Request $request):Response
    {
        $this->form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('email', EmailType::class)
            ->add('saveData', SubmitType::class, ['label' => 'Create Company'])
            ->getForm();

        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $data=$this->form->getData();
            $name=$data['name'];
            $description= $data['description'];
            $email= $data['email'];


            $this->logger->error(
                "Data is saved!",
                [json_encode(['name' => $name, 'description' => $description,'email'=>$email])]
            );
            return $this->redirectToRoute('company_controller');
        }
        return $this->render('company/index.html.twig', [
            'form'=>$this->form->createView()
        ]);
    }
}
