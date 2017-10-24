<?php
namespace Shield\BitBucketServer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shield\Shield\Contracts\Service;
/**
 * Class BitBucketServer
 *
 * @package \Shield\BitBucketServer
 */
class BitBucketServer implements Service
{
    public function verify(Request $request, Collection $config): bool
    {
        $generatedHash = hash_hmac('sha256', $request->getContent(), $config->get('token'));
        return hash_equals($generatedHash, $request->header('X-Hub-Signature'));
    }
    public function headers(): array
    {
        return ['X-Hub-Signature'];
    }
}