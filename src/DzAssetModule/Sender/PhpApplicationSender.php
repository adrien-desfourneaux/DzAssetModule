<?php

/**
 * Expéditeur d'Asset de type application PHP.
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

use Zend\Http\Header\Location;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\ResponseSender\HttpResponseSender;
use Zend\Mvc\ResponseSender\SendResponseEvent;

/**
 * Expéditeur d'Asset de type application PHP.
 *
 * @category Source
 * @package  DzAssetModule\Sender
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class PhpApplicationSender implements SenderInterface
{
    /**
     * True si asset envoyé. Faux sinon.
     *
     * @var boolean
     */
    protected $assetSent = false;

    /**
     * {@inheritdoc}
     */
    public function send($asset)
    {
        $file = $asset->getFile();
        $uri  = $asset->getUri();

        $openSession = function($alreadyOpened) {
            if (!$alreadyOpened) {
                session_start();
            }
        };

        $closeSession = function($alreadyOpened) {
            if (!$alreadyOpened) {
                session_write_close();
            }
        };

        $alreadyOpened = isset($_SESSION) ? true : false;

        $openSession($alreadyOpened);

        if (!isset($_SESSION['DzAssetModule\Sender'])) {
            $_SESSION['DzAssetModule\Sender'] = array();
        }

        if (!isset($_SESSION['DzAssetModule\Sender']['PhpApplication'])) {
            $_SESSION['DzAssetModule\Sender']['PhpApplication'] = array();
        } else {
            $phpApplication = $_SESSION['DzAssetModule\Sender']['PhpApplication'];

            if ($phpApplication['sent'] == true && $phpApplication['file'] != $asset->getFile()) {
                unset($_SESSION['DzAssetModule\Sender']['PhpApplication']);
                $closeSession($alreadyOpened);
                return;
            }
        }
        
        $_SESSION['DzAssetModule\Sender']['PhpApplication']['file'] = $file;
        $_SESSION['DzAssetModule\Sender']['PhpApplication']['sent'] = false;


        $closeSession($alreadyOpened);

        $response = new HttpResponse();
        $headers = $response->getHeaders();
        
        $headers->addHeader(Location::fromString('Location: ' . $uri));
        $response->setStatusCode(303);

        $sender = new HttpResponseSender();
        $event = new SendResponseEvent();
        $event->setResponse($response);
        $sender($event);

        $this->assetSent = true;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($asset)
    {
        if ($asset->getMimeType() == 'application/x-httpd-php') {
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