<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\DataValidation\DataValidatorInterface;

class ContactController extends AbstractController
{
    public $title = 'ContactControllerPHP';
    public $name = '';
    public $description='';
    public $email='';
    public $err_msg = '';
    private $logger;
    private $val;

    /**
     * __construct
     *
     * @param  mixed $logger
     * @param  mixed $val
     * @return void
     */
    public function __construct(LoggerInterface $logger, DataValidatorInterface $val)
    {
        $this->logger=$logger;
        $this->val=$val;
    }

    /**
     * loadTemplate
     *
     * @return Response
     */
    public function loadTemplate(): Response
    {
        return $this->render('contact/index.html.twig', [
            'title'=>$this->title,
            'err_msg'=>$this->err_msg,
            'name'=>$this->name,
            'description'=>$this->description,
            'email'=>$this->email
        ]);
    }    
        
    /**
     * will add the data in form as json and will display errors
     *
     * @param  mixed $request
     * @return Response
     */
    public function addContact(Request $request):Response
    {
        $this->name = $request->request->get('name');
        $this->description = $request->request->get('description');
        $this->email = $request->request->get('email');
        $valcon = $this->val->validData([$this->name, $this->description, $this->email]);
        if ($valcon == true) {
            $this->logger->notice(
                "submited succesfully",
                [json_encode(['name' => $this->name, 'email' => $this->email, 'description' => $this->description])]);
            return $this->redirectToRoute('contact_controller');
        } 
        else 
        {
            $this->err_msg = $this->val->errorVal();

            return $this->render('contact/index.html.twig', [
                'title'=>$this->title,
                'err_msg' =>$this->err_msg,
                'name' =>$this->name,
                'description' => $this->description,
                'email' => $this->email
            ]);
        }
    }
}
