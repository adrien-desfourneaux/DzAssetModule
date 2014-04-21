<?php

/**
 * Gestionnaire d'assets.
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
 * @package  DzAssetModule
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */

namespace DzAssetModule;

use DzAssetModule\Asset;
use DzAssetModule\Cache\AssetCache;
use DzAssetModule\Options\ModuleOptions;
use DzAssetModule\Resolver\FileResolver;
use DzAssetModule\Resolver\MimeResolver;
use DzAssetModule\Sender\AssetSender;
use DzAssetModule\Session\SessionManager;

use Zend\Http\PhpEnvironment\Request as HttpRequest;

/**
 * Gestionnaire d'asset.
 *
 * Renvoi le fichier d'asset demandé en URL.
 *
 * @category Source
 * @package  DzAssetModule
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class AssetManager
{
	/**
	 * Uri de la requête.
	 *
	 * @var string
	 */
	protected $uri;

	/**
	 * Cache d'asset.
	 *
	 * @var AssetCache
	 */
	protected $assetCache;

	/**
	 * Gestionnaire de session.
	 *
	 * @var SessionManager
	 */
	protected $sessionManager;

	/**
	 * Options du module.
	 *
	 * @var ModuleOptions
	 */
	protected $options;

	/**
	 * Constructeur de AssetManager.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$request = new HttpRequest();

		$this->uri            = $request->getRequestUri();
		$this->assetCache     = new AssetCache();
		$this->sessionManager = new SessionManager();
		$this->options        = null;

		$this->sessionManager->start();
	}

	/**
	 * Circuit court.
	 *
	 * Obtient un Asset depuis le cache
	 * et exécute l'envoi de l'asset.
	 *
	 * @return void
	 */
	protected function shortCircuit()
	{
		$uri        = $this->uri;
		$assetCache = $this->assetCache;

		$asset = $assetCache->get($uri);

		if ($asset !== null) {
			$this->finalize($asset);
		}
	}

	/**
	 * Charge et renvoie l'asset demandé en URL.
	 * à l'initialisation du Module.
	 *
	 * @return null|void
	 */
	public function load()
	{
		$this->shortCircuit();

		$uri = $this->uri;

		$options   = ModuleOptions::fromSession();
		$mimeTypes = $options->getMimeTypes();
		$baseUri   = $options->getBaseUri();
		$paths     = $options->getPaths();
		
		if (count($paths) == 0) {
			return null;
		}

		$fileResolver = new FileResolver();
		$fileResolver->setBaseUri($baseUri);
		$fileResolver->setPaths($paths);
		$file = $fileResolver->resolve($uri);

		if ($file == null) {
			return null;
		}
		
		$mimeResolver = new MimeResolver();
		$mimeResolver->setMimeTypes($mimeTypes);
		$mime = $mimeResolver->resolve($file);

		$asset = new Asset();
		$asset->setUri($uri);
		$asset->setFile($file);
		$asset->setMimeType($mime);
		$asset->setLastModifiedTime(filemtime($file));

		$this->finalize($asset);
	}

	/**
	 * Charge et renvoie l'asset demandé en URL
 	 * après le déclenchement de l'événement bootstrap
 	 * de l'application.
 	 *
 	 * @return void
 	 */
	public function loadLast()
	{
		$options = $this->getOptions();
		$options->toSession();

		$this->loadedLast = true;
		$this->load();
	}

	/**
	 * Finalize l'envoi de l'asset.
	 *
	 * Envoie l'asset et quitte subitement l'application.
	 *
	 * @param Asset $asset Asset à envoyer.
	 *
	 * @return void
	 */
	protected function finalize($asset)
	{
		$assetSender = new AssetSender();
		$assetSender->send($asset);

		$assetCache = $this->assetCache;
		$assetCache->put($asset);

		if ($assetSender->assetSent()) {
			$this->sessionManager->close();
			exit;
		}
	}

	/**
	 * Définit les options du module.
	 *
	 * @param ModuleOptions $options Nouvelles options.
	 *
	 * @return AssetManager
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * Obtient les options du module.
	 *
	 * @return ModuleOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}
}