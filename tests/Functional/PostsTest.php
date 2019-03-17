<?php

namespace Tests\Functional;

class PostsTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
    public function testListPosts()
    {
        $response = $this->runApp('GET', '/posts');

        $body = (string)$response->getBody();
        $posts = json_decode($body);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, count($posts));
    }

}
