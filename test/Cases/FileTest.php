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

    public function testCreateDir()
    {
        $res = $this->json('/file/create-dir', [
            'path' => '/技术部',
        ], [
            UserAuth::AUTH_TOKEN => UserAuthMockery::mockToken(1),
        ]);

        $this->assertSame(0, $res['code']);
    }

    public function testFileSave()
    {
        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/file/0', [
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

    public function testFileUpload()
    {
        /** @var ResponseInterface $response */
        $response = $this->client->request('POST', '/file/upload', [
            RequestOptions::HEADERS => [
                UserAuth::AUTH_TOKEN => UserAuthMockery::mockToken(1),
            ],
            RequestOptions::FORM_PARAMS => [
                'dirname' => '',
                'tags' => ['1', '2'],
                'summary' => '测试',
            ],
            RequestOptions::MULTIPART => [
                [
                    'name' => 'files0',
                    'contents' => fopen(BASE_PATH . '/README.md', 'r'),
                    'filename' => 'README.md',
                ],
                [
                    'name' => 'files1',
                    'contents' => fopen(BASE_PATH . '/phpunit.xml', 'r'),
                    'filename' => 'phpunit.xml',
                ],
            ],
        ]);

        $res = Json::decode((string) $response->getBody());
        $this->assertSame(0, $res['code']);
    }

    public function testGetTree()
    {
        $res = $this->get('/file/tree', [], [
            UserAuth::AUTH_TOKEN => UserAuthMockery::mockToken(1),
        ]);

        $this->assertSame(0, $res['code']);
    }

    public function testDeleteFile()
    {
        $res = $this->json('/file/delete', [
            'ids' => [
            ],
        ], [
            UserAuth::AUTH_TOKEN => UserAuthMockery::mockToken(1),
        ]);

        $this->assertSame(0, $res['code']);
    }
}
