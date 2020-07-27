<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    public function testGet()
    {
        $client = static::createClient();
        $client->request('GET', '/student/1');

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
 
    public function testGetFail()
    {
        $client = static::createClient();
        $client->request('GET', '/student/94581');
        $this->assertTrue($client->getResponse()->isNotFound());
    }
  
    public function testCreate()
    {
        $client = static::createClient();
        //missing name
        // how can I test a failing creation ? 
        try {
            // $client->request('POST', '/student',["surname"=>"test","birthDate"=>"2008-01-10"]);
        }catch(\Throwable $t){
            //$this->assertEquals(500, $client->getResponse()->getStatusCode());
        }
        $client->request('POST', '/student',["surname"=>"test","name"=>"test","birthdate"=>"2008-01-10"]);
        $data = json_decode($client->getResponse()->getContent());
        $this->assertGreaterThan(0, $data->id);
    }
}