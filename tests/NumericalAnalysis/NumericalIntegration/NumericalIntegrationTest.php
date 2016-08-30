<?php

namespace Math\NumericalAnalysis\NumericalIntegration;

class NumbericalIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateAbstractClassException()
    {
        // Instantiating NumericalIntegration (an abstract class)
        $this->setExpectedException('\Error');
        new Math\NumericalAnalysis\NumericalIntegration\NumericalIntegration;
    }
}
