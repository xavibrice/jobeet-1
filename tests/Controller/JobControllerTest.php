<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JobControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertEquals(
            1,
            $crawler->filter('a:contains("Post a Job")')->count()
        );
    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/job/create');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('button:contains("Create")')->form();

        $form['job[type]'] = 'part-time';
        $form['job[company]'] = 'Pentalog';
        $form['job[position]'] = 'Developer';
        $form['job[location]'] = 'Chisinau';
        $form['job[description]'] = 'some long long description';
        $form['job[howToApply]'] = 'email';
        $form['job[email]'] = 'email@email.com';

        $crawler = $client->submit($form);

        $this->assertEquals(0, $crawler->filter('.has-error')->count());

        $this->assertTrue($client->getResponse()->isRedirect());

        $this->assertStringStartsWith('/job/', $client->getResponse()->headers->get('location'));
    }

    public function testCreateWithoutEmail()
    {
        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/job/create');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('button:contains("Create")')->form();

        $form['job[type]'] = 'part-time';
        $form['job[company]'] = 'Pentalog';
        $form['job[position]'] = 'Developer';
        $form['job[location]'] = 'Chisinau';
        $form['job[description]'] = 'some long long description';
        $form['job[howToApply]'] = 'email';

        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('.has-error')->count());

        $this->assertFalse($client->getResponse()->isRedirect());
    }
}
