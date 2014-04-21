<?php

/**
 * Interface pour un expéditeur d'Asset.
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

/**
 * Interface pour un expéditeur d'Asset.
 *
 * @category Source
 * @package  DzAssetModule\Sender
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
interface SenderInterface
{
    /**
     * Expédie l'asset.
     *
     * @param Asset $asset Asset à expédier.
     *
     * @return void
     */
    public function send($asset);
    
    /**
     * Détermine si l'asset est valide pour cet expéditeur.
     *
     * @param Asset $asset Asset à tester.
     *
     * @return boolean
     */
    public function isValid($asset);

    /**
     * Obtient si l'asset a été envoyé ou non.
     *
     * @return boolean
     */
    public function assetSent();
}