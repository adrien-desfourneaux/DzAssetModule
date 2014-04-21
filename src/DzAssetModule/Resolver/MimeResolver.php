<?php

/**
 * Résolveur de type MIME.
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
 * Résolveur de type MIME.
 *
 * Obtient le type MIME en fonction de l'extension de fichier.
 *
 * @category Source
 * @package  DzAssetModule\Resolver
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class MimeResolver
{
    /**
     * Tableau 'extension' => 'type MIME'.
     *
     * @var array
     */
    protected $mimeTypes;
    
    /**
     * Obtient le type MIME depuis l'extension de fichier.
     *
     * @param string $fileName Nom de fichier.
     *
     * @return string Le type MIME trouvé ou "text/plain".
     */
    public function resolve($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (isset($this->mimeTypes[$extension])) {
            return $this->mimeTypes[$extension];
        }

        return 'text/plain';
    }

    /**
     * Obtient l'extension depuis le type MIME.
     *
     * @param string $mimeType Type MIME.
     *
     * @return null|string
     */
    public function getExtension($mimetype)
    {
        $extension = array_search($mimetype, $this->mimeTypes);

        return !$extension ? null : $extension;
    }

    /**
     * Charge les types MIME depuis un fichier.
     *
     * @param string $file Fichier à charger.
     *
     * @return void
     */
    protected function loadMimeFile($file)
    {
        $mimeTypes = array();

        // Format du fichier.
        // 'ext2mime' ou 'mime2ext'
        $format = null;

        if (($handle = fopen($file, "r")) !== false) {
            while (($buffer = fgets($handle)) !== false) {
                $fields = preg_split('/\s+/', $buffer);

                // commentaire
                if ($fields[0] == '#') {
                    continue;
                }

                // ligne vide
                if (!isset($fields[1]) && empty($fields[0])) {
                    continue;
                }

                // détection du format
                if (!$format) {
                    if (strpos($fields[0], '/') !== false) {
                        $format = 'mime2ext';
                    } else {
                        $format = 'ext2mime';
                    }
                }

                if ($format == 'ext2mime') {
                    $mimeTypes[$fields[0]] = $fields[1];
                } elseif ($format == 'mime2ext') {
                    $mimeTypes[$fields[1]] = $fileds[0];
                }
            }
            fclose($handle);
        }

        $this->setMimeTypes($mimeTypes);
    }

    /**
     * Définit les types MIME.
     *
     * @param array $mimeTypes Nouveaux types MIME.
     *
     * @return MimeResolver
     */
    public function setMimeTypes($mimeTypes)
    {
        if (is_array($mimeTypes)) {
            $this->mimeTypes = $mimeTypes;
        } elseif (is_file($mimeTypes)) {
            $this->loadMimeFile($mimeTypes);
        }
        
        return $this;
    }

    /**
     * Obtient les types MIME.
     *
     * @return array
     */
    public function getMimeTypes()
    {
        return $this->mimeTypes;
    }
}