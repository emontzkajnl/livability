<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2016 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Object;

/**
 * Interface JWKSetsInterface.
 */
interface JWKSetsInterface extends JWKSetInterface
{
    /**
     * @param \Jose\Object\JWKSetInterface $jwkset
     */
    public function addKeySet(JWKSetInterface $jwkset);
}
