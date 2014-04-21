<?php

/**
 * Aide pour les espaces de noms dans la session.
 *
 * @todo Tester cette classe.
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
 * @package  DzAssetModule\Session
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */

namespace DzAssetModule\Session;

/**
 * Aide pour les espaces de noms dans la session.
 *
 * Un espace de nom est un noeud de type array() dans $_SESSION.
 *
 * @category Source
 * @package  DzAssetModule\Session
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class NamespaceHelper
{
    /**
     * Cré un espace de nom dans la session.
     *
     * Une liste de paramètres dynamique permet d'ajouter
     * une infinité de sous-namespaces de l'espace de nom
     * racine.
     *
     * @param string $namespace Espace de nom racine.
     *
     * @return void
     */
    public static function create($namespace)
    {
        $namespaces = func_get_args();
        $namespacesBefore = array();

        $current = &$_SESSION;
        foreach($namespaces as $namespace) {
            static::createSub($current, $namespace, $namespacesBefore);
            $current = &$current[$namespace];
            $namespacesBefore[] = $namespace;
        }
    }

    /**
     * Cré un sous-namespace dans le tableau passé en argument.
     *
     * @param array  &$current         Référence vers le tableau de l'espace de nom.
     * @param string $namespace        Nouvel espace de nom.
     * @param array  $namespacesBefore Espaces de noms pères. Nécessaire pour afficher un message d'erreur.
     *
     * @return void
     */
    protected static function createSub(&$current, $namespace, $namespacesBefore = null)
    {
        if (!isset($current[$namespace])) {
            $current[$namespace] = array();
        } elseif (!is_array($current[$namespace])) {
            $namespaces = array_merge((array)$namespacesBefore, (array)$namespace);

            throw new \Exception(static::buildSessionString($namespaces) . ' ne peut être utilisé comme espace de nom par SessionManager car il contient une valeur.');
        }
    }

    /**
     * Construit une chaine représentant la variable d'accès à
     * une variable de session en utilisant les clés spécifiés.
     *
     * @param array $keys Clés du tableau de session.
     *
     * @return string
     */
    protected static function buildSessionString($keys)
    {
        $result = '$_SESSION';

        foreach ($keys as $key) {
            $result .= '["' . $key . '"]';
        }

        return $result;
    }
}