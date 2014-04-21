<?php

/**
 * Résolveur de fichier en fonction de l'URI.
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
 * @package  DzAssetModule\Resolver
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */

namespace DzAssetModule\Resolver;

/**
 * Résoud le fichier de ressource en fonction de l'URI.
 *
 * @category Source
 * @package  DzAssetModule\Resolver
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class FileResolver
{
    /**
     * URI de base.
     *
     * @var string
     */
    protected $baseUri = '/';

    /**
     * Tableau des chemins.
     *
     * Array au format 'uri' => 'path'.
     *
     * @var array
     */
    protected $paths;

    /**
     * Obtient le nom de fichier selon l'URI de requête.
     *
     * @param string $uri URI de la requête.
     *
     * @return string|null
     */
    public function resolve($uri)
    {
        $this->sanitize();

        $baseUri = $this->getBaseUri();
        $paths   = $this->getPaths();

        foreach ($paths as $url => $path) {

            $url = $baseUri . $url;

            if (substr($uri, 0, strlen($url)) == $url) {
                $file = $path . substr($uri, strlen($url));

                if (is_file($file)) {
                    return $file;
                }
            }
        }

        return null;
    }

    /**
     * Assainit les URI et les chemins.
     *
     * On supprime le caractère '/' en fin d'Uri de base et on
     * s'assure de la présence d'un caractère '/' en début d'Uri de base.
     * On supprime les caractères '/' en début et fin d'URI.
     * On supprime le caractère '/' ou '\' en fin de chemin si c'est un dossier.
     *
     * @return void
     */
    protected function sanitize()
    {
        $baseUri  = $this->getBaseUri();
        $paths    = $this->getPaths();
        $newPaths = array();

        $baseUri = rtrim($baseUri, '/');
        $baseUri = ltrim($baseUri, '/') . '/';
        $this->setBaseUri($baseUri);

        $uris = array_keys($paths);
        for ($i=0; $i<count($uris); $i++) {
            
            $uri  = $uris[$i];
            $uri = ltrim($uri, '/');
            $uri = rtrim($uri, '/');

            $path = $paths[$uris[$i]];

            if (is_dir($path)) {
                $path = rtrim($path, '/');
                $path = rtrim($path, '\\');
            }

            $newPaths[$uri] = $path;
        }

        $this->setPaths($newPaths);
    }

    /**
     * Définit l'URI de base.
     *
     * @param string $uri Nouvelle URI.
     *
     * @return UriResolver
     */
    public function setBaseUri($uri)
    {
        $this->baseUri = $uri;
        return $this;
    }

    /**
     * Obtient l'URI de base.
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }
    
    /**
     * Définit le tableau des chemins.
     *
     * @param array $paths Nouveau tableau.
     *
     * @return AssetResolver
     */
    public function setPaths($paths) 
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * Obtient le tableau des chemins.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }
}