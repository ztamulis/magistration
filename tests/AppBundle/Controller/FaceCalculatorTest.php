<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\UserEmotion;
use AppBundle\Service\FaceCalculator;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class FaceCalculatorTest extends TestCase
{
    /**
     * Unit test for face value calculation.
     */
    public function testCalculateFace()
    {
        $faceCalculator = new FaceCalculator();
        $this->assertEquals('sadness', $faceCalculator->calculateFace([0,0,0,0]));
    }
}
