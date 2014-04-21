<?php

/**
 * Fichier de module de DzAssetModule.
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

use DzAssetModule\AssetManager;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Classe module de DzAssetModule.
 *
 * @category Source
 * @package  DzAssetModule
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class Module implements
    AutoloaderProviderInterface,
    InitProviderInterface,
    BootstrapListenerInterface,
    ServiceProviderInterface
{
    /**
     * Gestionnaire d'asset.
     *
     * @var AssetManager
     */
    public $assetManager;

    /**
     * Retourne un tableau à parser par Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            /*'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),*/
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * Initialisation.
     *
     * @param  ModuleManagerInterface $manager Gestionnaire de Modules
     *
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        $this->assetManager = new AssetManager();
        $this->assetManager->load();
    }

    /**
     * Ecoute l'événement bootstrap.
     *
     * @param EventInterface $e Evénement.
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $serviceManager = $e->getTarget()->getServiceManager();
        $options        = $serviceManager->get('DzAssetModule\ModuleOptions');

        $this->assetManager->setOptions($options);
        $this->assetManager->loadLast();
    }

    /**
     * Doit retourner un objet de type \Zend\ServiceManager\Config
     * ou un tableau pour remplir un tel objet.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'DzAssetModule\ModuleOptions' => 'DzAssetModule\Factory\ModuleOptionsFactory',
            ),
        );
    }
}