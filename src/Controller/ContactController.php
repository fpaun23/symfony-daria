<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    public $title = 'ContactControllerPHP';
    public $user = 'Daria';

    public function loadTemplate(): Response
    {
        return $this->render('contact/index.html.twig', [
            'user' =>$this->user,
            'title'=>$this->title,
        ]);
    }
}
