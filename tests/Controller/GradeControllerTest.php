<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GradeControllerTest extends WebTestCase
{
    /** 
     * @todo : shuold load an empty base for test 
     * 
    */

    public function testCreate()
    {
        $client = static::createClient();
        //missing grade
        $client->request('POST', '/api/student/2/grade',["subject"=>"math"]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        //grade below 0 
        $client->request('POST', '/api/student/2/grade',["subject"=>"math", "grade"=>-1]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        // grade  0 
        $client->request('POST', '/api/student/2/grade',["subject"=>"math", "grade"=>0]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // grade  20 
        $client->request('POST', '/api/student/2/grade',["subject"=>"math", "grade"=>20]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        // grade  21 
        $client->request('POST', '/api/student/2/grade',["subject"=>"math", "grade"=>21]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

    }
}
