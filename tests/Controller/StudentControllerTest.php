<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    /** 
     * @todo : shuold load an empty base for test 
     * 
    */
    public function testGet()
    {
        $client = static::createClient();
        $client->request('GET', '/api/student/1');

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetFail()
    {
        $client = static::createClient();
        $client->request('GET', '/api/student/94581');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testCreate()
    {
        $client = static::createClient();
        //missing name
        $client->request('POST', '/api/student',["surname"=>"test","birthDate"=>"2008-01-10"]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        //missing surname
        $client->request('POST', '/api/student',["name"=>"test","birthDate"=>"2008-01-10"]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        //missing date
        $client->request('POST', '/api/student',["name"=>"test","suernam"=>"2008-01-10"]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
 
        $client->request('POST', '/api/student', ['surname' => 'test', 'name' => 'test', 'birthdate' => '2008-01-10']);
        $data = json_decode($client->getResponse()->getContent());
        $this->assertGreaterThan(0, $data->id);

        // update created student
        
        $client->request('POST', '/api/student/'.$data->id, ['surname'=>'flo']);
        $data = json_decode($client->getResponse()->getContent());
        $this->assertEquals('flo', $data->surname);
    }
}
