<?php

namespace AppBundle\Service;

use Symfony\Component\Stopwatch\Stopwatch;

class AnagramGenerator
{
    const DICT_FILE = '/usr/share/dict/words';
    /** @var Stopwatch $stopwatch */
    protected $stopwatch;

    public function __construct(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * Select the appropriate generation function and use it to generate possible matches
     *
     * @param string|array $inputString
     * @param string $algo
     *
     * @return array
     */
    public function generatePossible($inputString, $algo)
    {
        if ($this->stopwatch)
        {
            $this->stopwatch->start('service.anagramGenerator.generatePossible');
        }

        $function = 'generate'.$algo;

        $result = $this->{$function}($inputString);

        if ($this->stopwatch)
        {
            $this->stopwatch->stop('service.anagramGenerator.generatePossible');
        }

        return $result;
    }

    /**`
     * Generate all possible combinations of the provided letters using Heaps Algorithm
     *
     * @todo Need to work out how to add partial combinations
     *
     * @param string|array $inputString
     * @param int|null $length
     *
     * @return array
     */
    protected function generateHeapsAlgorithm($inputString, $length = null)
    {
        // We need the input as an array of chars, split it if required
        if (is_string($inputString)) $inputString = str_split($inputString);
        // If we don't have a length, work it out as this algorithm requires it
        if ($length === null) $length = count($inputString);

        $possibleStrings = [];

        // If we don't have anything left to process we should just return the input string
        if ($length === 0)
        {
            $possibleStrings[implode('', $inputString)] = implode('', $inputString);
            return $possibleStrings;
        }

        for ($i = 0; $i < $length; $i++)
        {   // Iterate through all chars in the input string
            // Recursively call the function with a consistent input string and reducing the length by 1
            $possibleStrings[] = $this->generateHeapsAlgorithm($inputString, $length-1);
            // Get the chars at two locations within the input string
            $swap =[
                ($length % 2) * $i => $inputString[$length - 1],
                $length - 1 => $inputString[($length % 2) * $i]

            ];
            // Swap the two chars identified in the previous line of code
            foreach ($swap as $destination => $char)
            {
                $inputString[$destination] = $char;
            }
        }

        // Merge the array; we do this here so we don't call array_merge within a loop
        return call_user_func_array('array_merge', $possibleStrings);
    }

    /**
     * Generate all possible combinations of the provided letters using simple array walking techniques
     *
     * @param string|array $inputString
     * @param string $prefix
     *
     * @return array
     */
    protected function generateSimple($inputString, $prefix = '')
    {
        // We need the input as an array of chars, split it if required
        if (is_string($inputString)) $inputString = str_split($inputString);
        // Need to know how many characters are in the input string
        $length = count($inputString);

        $possibleStrings = [];
        $childStrings = [];
        for ($i=0; $i < $length; $i++)
        {   // Iterate through all chars in the input string
            // Generate a new prefix, if no characters are left to process this is the possible work
            $workingPrefix = $prefix.$inputString[$i];
            // Remove a character from the input string
            $remainingChars = $inputString;
            array_splice($remainingChars, $i, 1);

            if (count($remainingChars))
            {   // If we have any characters left to process recursively call the function
                $childStrings[] = $this->generateSimple($remainingChars, $workingPrefix);
            }
            $possibleStrings[$workingPrefix] = $workingPrefix;
        }

        $childStrings[] = $possibleStrings;

        // Merge the array; we do this here so we don't call array_merge within a loop
        return call_user_func_array('array_merge', $childStrings);
    }

    /**
     * Determine which word validation to use and validate the words
     *
     * @param string[] $possibles
     *
     * @return string[]
     */
    public function filterPossibilities(&$possibles)
    {
        if ($this->stopwatch)
        {
            $this->stopwatch->start('service.anagramGenerator.filterPossibilities');
        }

        // Default to file search
        $function = 'filterFileSearch';
        if (extension_loaded('pspell'))
        {   // PSpell is available, use it
//            $function = 'filterPSpell';
        }
        // Call the selected function
        $valids = $this->{$function}($possibles);

        if ($this->stopwatch)
        {
            $this->stopwatch->stop('service.anagramGenerator.filterPossibilities');
        }

        return $valids;
    }

    /**
     * Validate words using PSpell PHP extension
     *
     * @param string[] $possibles
     *
     * @return string[]
     */
    protected function filterPSpell(&$possibles)
    {
        $valids = [];

        // Init PSpell
        $pspell = pspell_new('en');
        foreach ($possibles as $key => $possible)
        {   // Iterate through all possible strings
            if (pspell_check($pspell, $possible))
            {   // The word is valid, add to valids and remove from possibles
                $valids[] = $possible;
                unset($possibles[$key]);
            }
        }

        return $valids;
    }

    /**
     * Validate words using a simple file scan and match
     *
     * @param string[] $possibles
     *
     * @return string[]
     */
    protected function filterFileSearch(&$possibles)
    {
        $valids = [];

        // Open the dictionary file as read-only
        $fh = fopen(self::DICT_FILE, 'rb');
        while ($line = fgets($fh))
        {   // Loop through the dictionary file (removing newline chars)
            $line = trim($line);
            if (in_array($line, $possibles, true))
            {   // This dictionary word is in our list of possibilities, add to valids and remove from possibilities
                $valids[] = $line;
                unset($possibles[$line]);
            }
        }
        fclose($fh);

        return $valids;
    }
}