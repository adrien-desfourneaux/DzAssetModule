<?php

/**
 * Fichier source pour le ModuleOptionsFactory.
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
 * @package  DzAssetModule\Factory
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */

namespace DzAssetModule\Factory;

use DzAssetModule\Options\ModuleOptions;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Classe ModuleOptionsFactory.
 *
 * Classe usine pour les options du module.
 *
 * @category Source
 * @package  DzAssetModule\Factory
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class ModuleOptionsFactory implements FactoryInterface
{
	/**
     * Cré et retourne les options du module.
     *
     * @param  ServiceLocatorInterface $serviceLocator Localisateur de service.
     *
     * @return ModuleOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$config = $serviceLocator->get('Config');

    	if (isset($config['assets'])) {
    		$config = $config['assets'];
    	} else {
    		$config = array();
    	}

        return new ModuleOptions($config);
    }
}