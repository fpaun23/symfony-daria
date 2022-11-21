<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/')]

    public function loadTemplate():Response
    {
        return $this->render('company/index.html.twig', ['name'=>'Welcome']);
    }
  
   // #[Route('/add/{name}')]
    
    // public function add():Response
    // {
    //     return $this->render('add_company.html.twig');
    // }
}
