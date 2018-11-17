<?php

namespace AppBundle\Service;
use Symfony\Component\Stopwatch\Stopwatch;

class WordFinderValidator
{
    /** @var Stopwatch $stopwatch */
    protected $stopwatch;

    public function __construct(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * @param string $inputString
     *
     * @return bool
     */
    public function validateInput($inputString)
    {
        if ($this->stopwatch)
        {
            $this->stopwatch->start('service.wordfinderValidator.validateInput');
        }

        $valid = ctype_alpha($inputString);

        if ($this->stopwatch)
        {
            $this->stopwatch->stop('service.wordfinderValidator.validateInput');
        }

        return $valid;
    }
}