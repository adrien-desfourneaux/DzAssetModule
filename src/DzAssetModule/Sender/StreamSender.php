<?php

/**
 * Expéditeur d'Asset depuis un flux de fichier.
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
use DzAssetModule\Sender\SenderInterface;

use Zend\Http\Header\CacheControl;
use Zend\Http\Header\ContentType;
use Zend\Http\Response\Stream as StreamResponse;
use Zend\Mvc\ResponseSender\SendResponseEvent;
use Zend\Mvc\ResponseSender\SimpleStreamResponseSender;

/**
 * Expéditeur d'Asset depuis un flux de fichier.
 *
 * @category Source
 * @package  DzAssetModule\Sender
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class StreamSender implements SenderInterface
{
    /**
     * True si asset envoyé. False sinon.
     *
     * @var boolean
     */
    protected $assetSent;

	/**
     * {@inheritdoc}
     */
    public function send($asset)
    {
    	$file             = $asset->getFile();
        $mimeType         = $asset->getMimeType();

        $response = new StreamResponse();
        
        $headers = $response->getHeaders();
        $headers->addHeader(ContentType::fromString('Content-Type: ' . $mimeType));
        $headers->addHeader(CacheControl::fromString('Cache-Control: max-age=3600'));

        $stream = fopen($file, 'r');
        $response->setStream($stream);
        
        $sender = new SimpleStreamResponseSender();
        $event  = new SendResponseEvent();
        $event->setResponse($response);
        $sender($event);

        fclose($stream);

        if ($event->headersSent() && $event->contentSent()) {
            $this->assetSent = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($asset)
    {
    	return true;
    }

    /**
     * {@inheritdoc}
     */
    public function assetSent()
    {
        return $this->assetSent;
    }
}