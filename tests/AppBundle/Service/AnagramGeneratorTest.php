<?php

namespace Tests\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Service\AnagramGenerator;

class AnagramGeneratorTest extends KernelTestCase
{
    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(AnagramGenerator::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testGenerateHeapsAlgorithm()
    {
        $method = self::getMethod('generateHeapsAlgorithm');
        $service = new AnagramGenerator();

        $result = $method->invokeArgs($service, ['dog']);

        $this->assertInternalType('array', $result);
        $this->assertArraySubset(
            [
                'dog' => 'dog',
                'god' => 'god'
            ],
            $result
        );
    }

    public function testGenerateSimple()
    {
        $method = self::getMethod('generateSimple');
        $service = new AnagramGenerator();

        $result = $method->invokeArgs($service, ['dog']);

        $this->assertInternalType('array', $result);
        $this->assertArraySubset(
            [
                'dog' => 'dog',
                'god' => 'god',
                'do'  => 'do',
                'go'  => 'go'
            ],
            $result
        );
    }

    public function testFilterPSpell()
    {
        $this->markTestIncomplete('filterPSpell cannot be discretely tested as it is protected and requires parameters to be passed by reference.');
    }

    public function testFilterFileSearch()
    {
        $this->markTestIncomplete('filterFileSearch cannot be discretely tested as it is protected and requires parameters to be passed by reference.');
    }

    public function testGeneratePossible()
    {
        $service = new AnagramGenerator();

        $result = $service->generatePossible('dog', 'Simple');

        $this->assertInternalType('array', $result);
        $this->assertArraySubset(
            [
                'dog' => 'dog',
                'god' => 'god'
            ],
            $result
        );
    }

    public function testFilterPossiilities()
    {
        $service = new AnagramGenerator();

        $input = ['dog' => 'dog', 'god' => 'god', 'odg' => 'odg'];
        $result = $service->filterPossibilities($input);

        $this->assertInternalType('array', $input);
        $this->assertInternalType('array', $result);
        $this->assertArraySubset(
            [
                'odg' => 'odg'
            ],
            $input
        );
        $this->assertArraySubset(
            [
                'dog',
                'god'
            ],
            $result
        );
    }
}
