<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\UserEmotion;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultControllerTest extends WebTestCase
{
    /**
     * Should throw an exception because no data is available.
     */
    public function testPostEmgDataNoData()
    {
        $client = static::createClient();
        $client->request('GET', '/calculate_emotion');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Check if ajax controller returns what's expected.
     */
    public function testPostEmgData()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $client->request('POST', '/calculate_emotion', ['emg' => [1, 2, 3, 4]]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        /** @var UserEmotion $item */
        $item = $em->getRepository(UserEmotion::class)->findOneBy([], ['date' => 'DESC']);

        $this->assertEquals('test', $item->getUser()->getUsername());

        $this->assertGreaterThan(new \DateTime('-5 minutes'), $item->getDate());
        $this->assertEquals('sadness', $item->getEmotion());

        $this->assertEquals('"sadness"', $client->getResponse()->getContent());
    }

    /**
     * Emotion list.
     */
    public function testEmotionList()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ]);
        $client->request('GET', '/emotion_list');

        $this->assertGreaterThan(0, $client->getCrawler()->filter('html:contains("sadness")')->count());
    }

    /**
     * Unit test for index action.
     * vendor/bin/phpunit
     */
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('POST', '/');

        $this->assertEquals(1, $client->getCrawler()->filter('html:contains("SVEIKIIIIIIIII")')->count());
    }
}
