<?php

/**
 * Fichier d'options pour le Module DzAssetModule.
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
 * @package  DzAssetModule\Options
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */

namespace DzAssetModule\Options;

use DzAssetModule\Session\NamespaceHelper;

use Zend\Stdlib\AbstractOptions;

/**
 * Classe d'options pour le Module DzAssetModule
 *
 * @category Source
 * @package  DzAssetModule\Options
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class ModuleOptions extends AbstractOptions
{
	/**
	 * Mime Types.
	 *
	 * Peut être un tableau php 'extension' => 'mime.types' ou un fichier
	 * de format csv avec séparateur espace.
	 *
	 * @var string|array
	 */
	protected $mimeTypes;

	/**
	 * Uri de base.
	 *
	 * C'est l'Uri du dossier public de l'application.
	 *
	 * @var string
	 */
	protected $baseUri = '/';

	/**
     * Chemins, Couples 'url' => 'path'.
     *
     * Si l'url commence par 'url', alors chercher le fichier dans 'path'.
     * 'url' est un chaîne que l'on trouve en début d'url.
     * 'path' est le chemin vers le dossier de destination.
     *
     * Généralement 'url' contient le nom du module, et 'path' contient le
     * chemin relatif vers un dossier public du module qui contient les assets.
     *
     * @var array
     */
	protected $paths = array();

	/**
	 * Constructeur de ModuleOptions.
	 *
	 * @param  array|Traversable|null $options
	 *
	 * @return void
	 */
    public function __construct($options = null)
    {
    	parent::__construct($options);

    	if ($this->getMimeTypes() == null) {
			$mimeTypes = include __DIR__ . '/../../../config/mime.types.php';
			$this->setMimeTypes($mimeTypes);
    	}
	}

	/**
	 * Définit les mime.types.
	 *
	 * @param string|array Nouveaux mime.types.
	 *
	 * @return ModuleOptions
	 */
	public function setMimeTypes($mimeTypes)
	{
		$this->mimeTypes = $mimeTypes;
		return $this;
	}

	/**
	 * Obtient les mime.types.
	 *
	 * @return string|array
	 */
	public function getMimeTypes()
	{
		return $this->mimeTypes;
	}

	/**
	 * Définit l'Uri de base.
	 *
	 * @param string $uri Nouvelle Uri de base.
	 *
	 * @return ModuleOptions
	 */
	public function setBaseUri($uri)
	{
		$this->baseUri = $uri;
		return $this;
	}

	/**
	 * Obtient l'Uri de base.
	 *
	 * @return string
	 */
	public function getBaseUri()
	{
		return $this->baseUri;
	}

	/**
	 * Définit les chemins.
	 *
	 * @param array $paths Nouveaux chemins.
	 *
	 * @return ModuleOptions
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;
		return $this;
	}

	/**
	 * Obtient les chemins.
	 *
	 * @return array
	 */
	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * Charge les options du module depuis la session.
	 *
	 * @return ModuleOptions
	 */
	public static function fromSession()
	{
		NamespaceHelper::create('DzAssetModule\ModuleOptions');

		$options = $_SESSION['DzAssetModule\ModuleOptions'];

		if (count($options) == 0) {
			return new ModuleOptions();
		}

		$mimeTypes = $options['mimeTypes'];
		$baseUri   = $options['baseUri'];
		$paths     = $options['paths'];

		return new ModuleOptions(
			array(
				'mime_types' => $mimeTypes,
				'base_uri'   => $baseUri,
				'paths'      => $paths,
			)
		);
	}

	/**
	 * Sauvegarde les options en session.
	 *
	 * @return ModuleOptions
	 */
	public function toSession()
	{
		NamespaceHelper::create('DzAssetModule\ModuleOptions');

		$options = &$_SESSION['DzAssetModule\ModuleOptions'];

		$options['mimeTypes'] = $this->getMimetypes();
		$options['baseUri']   = $this->getBaseUri();
		$options['paths']     = $this->getPaths();

		return $this;
	}
}