<?php

namespace Shield\BitBucketServer\Test\Unit;

use PHPUnit\Framework\Assert;
use Shield\Shield\Contracts\Service;
use Shield\Testing\TestCase;
use Shield\BitBucketServer\BitBucketServer;

/**
 * Class ServiceTest
 *
 * @package \Shield\BitBucketServer\Test\Unit
 */
class BitBucketServerTest extends TestCase
{
    /**
     * @var \Shield\BitBucketServer\BitBucketServer
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new BitBucketServer;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new BitBucketServer);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $token = 'teikoK33Y$$';
        $this->app['config']['shield.services.bitbucketserver.token'] = $token;
        $content = 'XXX Code Only';
        $request = $this->request($content);
        $headers = [
            'X-Hub-Signature' => hash_hmac('sha256', $content, $token),
        ];
        $request->headers->add($headers);
        Assert::assertTrue($this->service->verify($request, collect($this->app['config']['shield.services.bitbucketserver'])));
    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $content = 'XXX Code Only';
        $request = $this->request($content);
        $headers = [
            'X-Hub-Signature' => hash_hmac('sha256', $content, 'bad request'),
        ];
        $request->headers->add($headers);
        Assert::assertFalse($this->service->verify($request, collect($this->app['config']['shield.services.bitbucketserver'])));
    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset(['X-Hub-Signature'], $this->service->headers());
    }
}
