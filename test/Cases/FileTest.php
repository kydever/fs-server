<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace HyperfTest\Cases;

use GuzzleHttp\RequestOptions;
use Hyperf\Utils\Codec\Json;
use HyperfTest\HttpTestCase;
use KY\WorkWxUser\Testing\UserAuthMockery;
use KY\WorkWxUser\UserAuth;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 * @coversNothing
 */
class FileTest extends HttpTestCase
{
    /**
     * @depends testFileSave
     */
    public function testFileIndex()
    {
        $res = $this->get('/file', [
            'dirname' => '/',
        ]);

        $this->assertSame(0, $res['code']);
    }

    /**
     * @depends testFileSave
     */
    public function testFileInfo()
    {
        $res = $this->get('/file/1');

        $this->assertSame(0, $res['code']);
    }

    /**
     * @depends testFileSave
     */
    public function testFileDownloadUrl()
    {
        $res = $this->json('/file/download-url', [
            'ids' => [1],
        ]);

        $this->assertSame(0, $res['code']);
    }

    public function testFileSave()
    {
        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/file/34', [
            RequestOptions::HEADERS => [
                UserAuth::AUTH_TOKEN => UserAuthMockery::mockToken(1),
            ],
            RequestOptions::FORM_PARAMS => [
                'path' => '/markdown/fs-server/README.md',
                'tags' => [
                    'Markdown',
                ],
            ],
            RequestOptions::MULTIPART => [
                [
                    'name' => 'file',
                    'contents' => fopen(BASE_PATH . '/README.md', 'r'),
                    'filename' => 'README.md',
                ],
            ],
        ]);

        $res = Json::decode((string) $response->getBody());
        $this->assertSame(0, $res['code']);
    }
}
