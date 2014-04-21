<?php

/**
 * Classe de cache pour les assets.
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
 * @package  DzAssetModule\Cache
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */

namespace DzAssetModule\Cache;

use DzAssetModule\Asset;
use DzAssetModule\Session\NamespaceHelper;

/**
 * Classe de cache pour les assets.
 *
 * La gestion de la session se fait à la main avec
 * la variable $_SESSION. L'utilisation de Zend\Session\Container
 * perdait les données entre deux requêtes.
 *
 * @category Source
 * @package  DzAssetModule\Cache
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class AssetCache
{
	/**
	 * Copie des assets présents en session.
	 *
	 * Chaque modification de $assets doit être
	 * suivie d'un appel à synchronize() pour
	 * refléter ces modifications sur la session.
	 *
	 * @var array
	 */
	public $assets = array();

	/**
	 * Constructeur de AssetCache.
	 *
	 * @return void
	 */
	public function __construct()
	{
		NamespaceHelper::create('DzAssetModule\AssetCache', 'assets');

		$assets = $_SESSION['DzAssetModule\AssetCache']['assets'];

		foreach ($assets as $asset) {
			$this->assets[] = unserialize($asset);
		}
	}

	/**
	 * Obtient un asset depuis son Uri.
	 *
	 * @param string $uri Uri à rechercher.
	 *
	 * @return null|Asset
	 */
	public function get($uri)
	{
		$assets = $this->assets;
		$index  = $this->findIndexFromUri($uri);

		if ($index !== null) {
			return $assets[$index];
		}

		return null;
	}

	/**
	 * Met un asset en cache.
	 *
	 * @param Asset $asset Asset à mettre en cache.
	 *
	 * @return void
	 */
	public function put($asset)
	{
		$index = $this->findIndexFromUri($asset->getUri());

		if ($index !== null) {
			$this->assets[$index] = $asset;
			$this->synchronize($index);
		} else {
			$this->assets[] = $asset;
			$this->synchronize(-1);
		}
	}

	/**
	 * Met à jour la session à partir du tableau $assets.
	 *
	 * La synchronization ne se fait que dans un seul sens : de la variable $assets
	 * vers la session $_SESSION. Donc les changements directes dans $_SESSION sans passer
	 * par la classe AssetCache ne seront pas pris en compte.
	 *
	 * @param integer $index Index optionnel à mettre à jour.
	 *                       -1 correspond au dernier élément du tableau $assets.
	 *                       null met à jour tous les éléments de la session.
	 *
	 * @return void
	 */
	protected function synchronize($index = null)
	{
		$assets      = $this->assets;

		if ($index === null) {
			foreach ($assets as $asset) {
				$_SESSION['DzAssetModule\AssetCache']['assets'][] = serialize($asset);
			}
		} elseif ($index == -1) {
			$lastIdx = count($assets)-1;
			$_SESSION['DzAssetModule\AssetCache']['assets'][$lastIdx] = serialize($assets[$lastIdx]);
		} else {
			$_SESSION['DzAssetModule\AssetCache']['assets'][$index] = serialize($assets[$index]);
		}
	}

	/**
	 * Obtient l'index d'un asset présent en cache depuis son Uri.
	 *
	 * @param string $uri Uri à chercher.
	 *
	 * @return null|integer
	 */
	protected function findIndexFromUri($uri)
	{
		$assets = $this->assets;

		for ($i=0; $i<count($assets); $i++) {
			if ($assets[$i]->getUri() == $uri) {
				return $i;
			}
		}

		return null;
	}
}