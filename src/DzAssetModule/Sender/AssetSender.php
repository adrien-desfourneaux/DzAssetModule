<?php

/**
 * Expéditeur d'Asset.
 *
 * PHP version 5.3.0
 *
 * Copyright 2014 Adrien Desfourneaux
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category Source
 * @package  DzAssetModule\Sender
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */

namespace DzAssetModule\Sender;

use DzAssetModule\Asset;
use DzAssetModule\Sender\NotModifiedSender;
use DzAssetModule\Sender\PhpApplicationSender;
use DzAssetModule\Sender\SenderInterface;
use DzAssetModule\Sender\StreamSender;

/**
 * Expéditeur d'Asset.
 *
 * @category Source
 * @package  DzAssetModule\Sender
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class AssetSender implements SenderInterface
{
    /**
     * True si asset envoyé. False sinon.
     *
     * @var boolean
     */
    protected $assetSent = false;

    /**
     * {@inheritdoc}
     */
    public function send($asset)
    {
        $sender = new PhpApplicationSender();
        if ($sender->isValid($asset)) {
            $sender->send($asset);
            $this->assetSent = $sender->assetSent();
            return;
        }

        $sender = new NotModifiedSender();
        if ($sender->isValid($asset)) {
            $sender->send($asset);
            $this->assetSent = $sender->assetSent();
            return;
        }

        $asset->updateLastModifiedTime();

        $sender = new StreamSender();
        if ($sender->isValid($asset)) {
            $sender->send($asset);
            $this->assetSent = $sender->assetSent();
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($asset)
    {
        $file = $asset->getFile();

        if (is_file($file)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function assetSent()
    {
        return $this->assetSent;
    }
}