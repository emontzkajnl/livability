<?php

/**
 * Copyright (c) 2024, Ramble Ventures
 */

namespace PublishPress\Future\Modules\Expirator\Interfaces;

defined('ABSPATH') or die('Direct access not allowed.');

interface MigrationInterface
{
    public function migrate();
}
