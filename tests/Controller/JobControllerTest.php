<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JobControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(
            1,
            $crawler->filter('a:contains("Post a Job")')->count()
        );
    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/job/create');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

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
}
