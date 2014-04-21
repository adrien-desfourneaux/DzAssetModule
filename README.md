DzAssetModule
=========

Module de gestion des assets pour ZF2.

DzAssetModule permet de charger directement les fichiers de ressources (scripts js, styles css, autres) depuis un répertoire du module concerné. Ainsi on peut garder les assets au sein du module sans avoir à les déployer dans le dossier /public de Zend Framework 2.

DzAssetModule dans le principe est semblable à [RWOverdijk/AssetManager](https://github.com/RWOverdijk/AssetManager) à la très grande différence que les assets sont chargés très tôt dans le cycle d'exécution de ZF2, d'où une très grande rapidité de chargement des fichiers de ressources.

Installation
----------------

Via composer : bientôt...

Ajouter *DzAssetModule* dans votre fichier *application.config.php* en **première position** :

Fichier *application.config.php* :

	return array(
    	'modules' => array(
        	'DzAssetModule',
        	
        	// autres modules
        	// ......
        ),
    );
   
Mettre *DzAssetModule* en première position assure qu'il sera chargé en premier donc le chargement des fichiers de ressources sera plus rapide.

Configuration
------------------

Exemple si on veut stocker ses assets dans un dossier */public* de son module.

Dans le fichier *module.config.php* :
	
	return array(
	    'assets' => array(
    	    'paths' => array(
        	    'mymodule' => __DIR__ . '/../public',
	        ),
	    ),
	);

Si un fichier *chemin/vers/mymodule/public/css/style.css* existe alors une requête à */mymodule/css/style.css* renverra le contenu du fichier *style.css* avec le type MIME *text/css*. Par contre si le fichier n'existe pas, alors Zend Framework 2 continuera son exécution normalement.

Les informations du fichier de ressource (chemin du fichier, type MIME) sont mis en cache dans la session à la première requête. A partir de la deuxième requête, on utilise les informations du cache d'où un gain de rapidité à partir de la deuxième requête.

Licence
--------------

Copyright 2014 Adrien Desfourneaux

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.