<?php

namespace AppBundle\Controller;

use AppBundle\Service\AnagramGenerator;
use AppBundle\Service\WordFinderValidator;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class WordFinderController extends FOSRestController
{
    protected $validator;

    /**
     * Find all anagrams of the provided string
     *
     * @param Request $request
     * @param string $input
     *
     * @Route("/wordfinder/{input}", name="wordfinder", methods={ "GET" })
     *
     * @return Response
     */
    public function indexAction(Request $request, $input)
    {
        // Validate the input only contains characters
        /** @var WordFinderValidator $validator */
        $validator = $this->container->get(WordFinderValidator::class);
        if (!$validator->validateInput($input))
        {
            throw new BadRequestHttpException('Invalid anagram letter source.  Only alphabetical characters are allowed.');
        }

        // The word database (dictionary) will be case-insensitive, so we should be too
        $input = strtolower($input);

        // Define the anagram generator
        $generator = $this->container->get(AnagramGenerator::class);

        // Generate possibilities
        $possibles = $generator->generatePossible($input, $this->container->getParameter('wordfinder_generator'));

        // Filter possibilities to valid
        $valids = $generator->filterPossibilities($possibles);

        // Prepare the return data
        $data = [
            'code' => 200,
            'message' => 'success',
            'input' => $input,
            'invalid' => array_values($possibles),
            'valid' => $valids
        ];
        $view = $this->view($data, 200)
            ->setTemplate('default/json.html.twig');

        return $this->handleView($view);
    }
}
