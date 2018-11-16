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
        $data = [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ];
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
            'message' => 'success'
        ];

        $view = $this->view($data, 200)
            ->setTemplate('default/json.html.twig')
            ->setTemplateVar('other');
        // replace this example code with whatever you need
        return $this->handleView($view);
    }
}
