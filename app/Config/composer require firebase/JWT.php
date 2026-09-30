<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public string $key = 'my-secret-key-123456';

    public string $algorithm = 'HS256';

    public int $expire = 3600; // 1 hour
}