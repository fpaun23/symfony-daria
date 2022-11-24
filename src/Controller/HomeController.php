<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public $contact = 'contact';
    public $company = 'company';
    
    /**
     * loadTemplate
     *
     * @return Response
     */
    public function loadTemplate(): Response
    {
        return $this->render('home/index.html.twig', [
            'contact' =>$this->contact,
            'company'=>$this->company,
        ]);
    }
}
