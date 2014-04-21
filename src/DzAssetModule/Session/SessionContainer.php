<?php

/**
 * @todo: Implémenter un SessionContainer.
 */

    /**
     * Référence vers le tableau racine dans l'espace de nom dans la session.
     *
     * @var &array
     */
    //protected $root;

    /**
     * Obtient une variable dans l'espace de nom dans la session.
     *
     * @param string $key Clé de la variable à obtenir. Si null renvoie l'array de l'espace de nom.
     *
     * @return mixed
     */
    /*public function get($key = null)
    {
        if ($key === null) {
            return $this->root;
        }

        return $this->root[$key];
    }*/

    /**
     * Définit une variable dans l'espace de nom dans la session.
     *
     * @param string $key   Clé de la variable à definir.
     * @param mixed  $value Valeur à définir.
     *
     * @return void
     */
    /*public function set($key, $value)
    {
        $this->root[$key] = $value;
    }*/

    /**
     * Constructeur de SessionManager.
     *
     * Le premier paramètre est l'espace de nom à utiliser
     * il correspond à la première clé dans la variable 
     * $_SESSION. Des paramètres dynamiques permettent
     * d'utiliser des espaces de noms en plus. 
     *
     * @param string $namespace Espace de nom à utiliser.
     *
     * @return void
     */
    /*public function __construct($namespace = null)
    {
        
    }*/