<?php

/**
 * Entité "Asset" ou "Fichier de Ressource".
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

/**
 * Entité "Asset" ou "Fichier de ressource"
 *
 * @category Source
 * @package  DzAssetModule
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class Asset
{
	/**
	 * URI de l'asset.
	 *
	 * @var string
	 */
	protected $uri;

	/**
     * Nom du fichier d'asset.
     *
     * @var string
     */
    protected $file;

    /**
     * Type MIME de l'asset.
     *
     * @var string
     */
    protected $mimeType = 'text/plain';

    /**
     * Timestamp de dernière modification.
     *
     * @var integer
     */
    protected $lastModifiedTime;

    /**
	 * Définit l'URI de l'asset.
	 *
	 * @param string $uri Nouvelle URI.
	 *
	 * @return Asset
	 */
    public function setUri($uri)
    {
    	$this->uri = $uri;
    	return $this;
    }

    /**
     * Obtient l'URI de l'asset.
     *
     * @return string
     */
    public function getUri()
    {
    	return $this->uri;
    }

    /**
     * Définit le fichier d'asset.
     *
     * @param string $file Nouveau fichier.
     *
     * @return Asset
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Obtient le fichier d'asset.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Définit le type Mime de l'asset.
     *
     * @param string $mimeType Nouveau type Mime.
     *
     * @return Asset
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Obtient le type Mime de l'asset.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Définit la date de dernière modification.
     *
     * @param integer $time Nouveau timestamp.
     *
     * @return Asset
     */
    public function setLastModifiedTime($time)
    {
        $this->lastModifiedTime = $time;
        return $this;
    }

    /**
     * Obtient la date de dernière modification.
     *
     * @return integer Timestamp.
     */
    public function getLastModifiedTime()
    {
        return $this->lastModifiedTime;
    }

    /**
     * Met à jour la date de dernière modification.
     *
     * @return void
     */
    public function updateLastModifiedTime()
    {
        $file = $this->getFile();

        $lastModifiedTime = filemtime($file);

        $this->setLastModifiedTime($lastModifiedTime);
    }
}