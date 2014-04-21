<?php

/**
 * Expéditeur d'Asset si asset non modifié.
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

use Zend\Http\PhpEnvironment\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Http\Header\LastModified;
use Zend\Http\Header\Etag;
use Zend\Mvc\ResponseSender\HttpResponseSender;
use Zend\Mvc\ResponseSender\SendResponseEvent;

/**
 * Expéditeur d'Asset si asset non modifié.
 *
 * @category Source
 * @package  DzAssetModule\Sender
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class NotModifiedSender implements SenderInterface
{
    /**
     * Vrai si envoi effectué. Faux sinon.
     *
     * @var boolean
     */
    protected $assetSent = false;

	/**
	 * {@inheritdoc}
	 */
	public function send($asset)
	{
		$file             = $asset->getFile();
		$lastModifiedTime = $asset->getLastModifiedTime();
		$lastModifiedDate = \DateTime::createFromFormat('U', $lastModifiedTime);

		$response = new HttpResponse();
		$headers = $response->getHeaders();

		$lastModified = new LastModified();
		$lastModified->setDate($lastModifiedDate);
		$headers->addHeader($lastModified);

		$etag = md5_file($file);
		$headers->addHeader(Etag::fromString('Etag: ' . $etag));

		$sender = new HttpResponseSender();
		$event  = new SendResponseEvent();
		$event->setResponse($response);
		$sender($event);

        if ($event->headersSent() && $event->contentSent()) {
            $this->assetSent = true;
        }
	}

	/**
     * {@inheritdoc}
     */
    public function isValid($asset)
    {
    	// Last Modified Time

    	$file                = $asset->getFile();
    	$lastModifiedTime    = $asset->getLastModifiedTime();
        $currentModifiedTime = filemtime($file);

    	if ($lastModifiedTime != $currentModifiedTime) {
    		return false;
    	}

    	// Request Last-Modified-Since && Etag

    	$request = new HttpRequest();
    	$server  = $request->getServer();
    	$etag    = md5_file($file);

    	/*if (!(strtotime($server->get('HTTP_IF_MODIFIED_SINCE')) == $lastModifiedTime ||
		trim($server->get('HTTP_IF_NONE_MATCH')) == $etag)) {
    		return false;
		}*/

    	//return true;
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