<?php

namespace Tests\Functional;

use Models\Post;

class PostsTest extends BaseTestCase
{
    public function testListPosts()
    {
        $response = $this->runApp('GET', '/posts');

        $body = (string)$response->getBody();
        $posts = json_decode($body);
        $expected = [
            (object) [
                'id' => 3,
                'title' => 'Adding Photo Albums',
                'createdTime' => '2019-03-10 20:53:07',
                'content' => "I started added some old photo albums of previous trips and activities to the site.\nI will be posting more soon.",
            ],
            (object) [
                'id' => 2,
                'title' => 'Old Design Retired',
                'createdTime' => '2019-03-08 21:01:46',
                'content' => "I just switched from my sbuberl.com’s old Jekyll-based web design to the current design I was playing around with for a little while in a separate GitHub repo.\nPlus I can customize the syntax highlighting to any style I wish now.",
                ],
            (object) [
                'id' => 1,
                'title' => 'Welcome to my blog',
                'createdTime' => '2018-11-26 02:52:40',
                'content' => "Thanks for joining me on my new blog.\nI primarily use it for site news and updates (such as new release or new content added).\nI’m playing around with moving the fSQL docs back to the new site layout now that I’ve gotten rid of Jekyll.",
            ],
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, $posts);
    }

    public function testGetPost()
    {
        $response = $this->runApp('GET', '/posts/2');

        $body = (string)$response->getBody();
        $post = json_decode($body);
        $expected = (object) [
            'id' => 2,
            'title' => 'Old Design Retired',
            'createdTime' => '2019-03-08 21:01:46',
            'content' => "I just switched from my sbuberl.com’s old Jekyll-based web design to the current design I was playing around with for a little while in a separate GitHub repo.\nPlus I can customize the syntax highlighting to any style I wish now.",
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, $post);
    }

    public function testGetPostNotFound()
    {
        $response = $this->runApp('GET', '/posts/12');

        $body = (string)$response->getBody();
        $error = json_decode($body);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($error, "Could not find post");
    }

    public function testCreatePost()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $newData = ['title' => 'New Test Post',
                    'createdTime' => $now,
                    'content' => 'New blog post body',
                ];
        $response = $this->runApp('POST', '/posts', $newData);

        $body = (string)$response->getBody();
        $post = json_decode($body);
        $expected = (object) [
            'id' => 4,
            'title' => $newData['title'],
            'createdTime' => $newData['createdTime'],
            'content' => $newData['content'],
        ];

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($expected, $post);
    }

    public function testEditPost()
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $newData = ['title' => 'Test Title 12345',
                    'content' => 'New blog post body',
                ];
        $response = $this->runApp('PUT', '/posts/3', $newData);

        $body = (string)$response->getBody();
        $post = json_decode($body);
        $expected = (object) [
            'id' => 3,
            'title' =>  $newData['title'],
            'createdTime' => '2019-03-10 20:53:07',
            'content' => $newData['content'],
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, $post);
    }

    public function testEditPostNotFound()
    {
        $response = $this->runApp('PUT', '/posts/12');

        $body = (string)$response->getBody();
        $error = json_decode($body);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($error, "Could not find post");
    }

    public function testDeletePost()
    {
        $response = $this->runApp('DELETE', '/posts/3');

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeletePostNotFound()
    {
        $response = $this->runApp('DELETE', '/posts/12');

        $body = (string)$response->getBody();
        $error = json_decode($body);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($error, "Could not find post");
    }
}
