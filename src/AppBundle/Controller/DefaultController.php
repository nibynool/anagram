<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FOSRestController
{
    /**
     * Default Symfony page to verify successful installation
     *
     * @Route("/", name="default", methods={ "GET" })
     *
     * @param Request $request
     *
     * @todo Delete this function and associated tests before finalising for production deployment
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $data = [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ];
        $view = $this->view($data, 200)
            ->setTemplate('default/index.html.twig');

        return $this->handleView($view);
    }

    /**
     * Ping end-point
     *
     * @Route("/ping", name="ping", methods={ "GET" })
     *
     * @param Request $request
     *
     * @todo Add functionality to verify all required services are up and running
     *
     * @return Response
     */
    public function pingAction(Request $request)
    {
        $data = [
            'code' => 200,
            'message' => 'success'
        ];

        $view = $this->view($data, 200)
            ->setTemplate('default/json.html.twig');

        return $this->handleView($view);
    }
}
