<?php

namespace App\Traits;

use Hashids\Hashids;

trait HashIdConverter
{
    private readonly int $hashLength;

    public function __construct()
    {
        $this->hashLength = 15;
    }

    public function hash(int $id, string $key): string
    {
        $hashObject = new Hashids($key, $this->hashLength);
        return $hashObject->encode($id);
    }

    public function deHash(string $hashedId, string $key): int
    {
        $hashObject = new Hashids($key, $this->hashLength);
        $deHashedId = last($hashObject->decode($hashedId));
        return (int)$deHashedId;
    }
}
