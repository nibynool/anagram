<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FOSRestController
{
    /**
     * @Route("/", name="default")
     */
    public function indexAction(Request $request)
    {
        $data = null;
        $view = $this->view($data, 200)
            ->setTemplate('default/index.html.twig');
        // replace this example code with whatever you need
        return $this->handleView($view);
    }

    /**
     * @Route("/ping", name="ping", methods={"GET"})
     */
    public function pingAction(Request $request)
    {
        $data = [
            'status' => 'success'
        ];

        $view = $this->view($data, 200)
            ->setTemplate('default/json.html.twig');
        // replace this example code with whatever you need
        return $this->handleView($view);
    }
}
