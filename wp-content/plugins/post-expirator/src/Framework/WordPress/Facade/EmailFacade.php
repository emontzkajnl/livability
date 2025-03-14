<?php

/**
 * Copyright (c) 2024, Ramble Ventures
 */

namespace PublishPress\Future\Framework\WordPress\Facade;

defined('ABSPATH') or die('Direct access not allowed.');

class EmailFacade
{
    public function send($to, $subject, $message, $headers = '', $attachments = [])
    {
        // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail
        return wp_mail($to, $subject, $message, $headers, $attachments);
    }
}
