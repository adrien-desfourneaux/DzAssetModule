<?php

/**
 * Gestionnaire de session.
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
 * Gestionnaire de session.
 *
 * @category Source
 * @package  DzAssetModule\Session
 * @author   Adrien Desfourneaux (aka Dieze) <dieze51@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License Version 2.0
 * @link     https://github.com/dieze/DzAssetModule
 */
class SessionManager
{
    /**
     * Etat de la session ouverte par le
     * SessionManager.
     *
     * @var boolean
     */
    protected $sessionOpened = false;

    /**
     * Démarre la session ou non selon si elle
     * est déjà ouverte.
     *
     * @return void
     */
    public function start()
    {
        if (!$this->sessionOpened && !$this->isSessionStarted()) {
            session_start();
            $this->sessionOpened = true;
        }
    }

    /**
     * Ferme la session ou non selon si elle
     * était ouverte avant.
     *
     * @return void
     */
    public function close()
    {
        if ($this->sessionOpened) {
            session_write_close();
        }
    }

    /**
     * Obtient si la session est démarrée ou non.
     *
     * @return boolean
     */
    private function isSessionStarted()
    {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? true : false;
        } else {
            $setting = 'session.use_trans_sid';
            $current = ini_get($setting);
            if (FALSE === $current)
            {
                throw new UnexpectedValueException(sprintf('Setting %s does not exists.', $setting));
            }
            $result = @ini_set($setting, $current); 
            return $result !== $current;
        }
    }
}