<?php

namespace Poki;

/**
 * Decompose l'url pour en extraire les informations de route: controlleur, action, variables globales éventuelles
 */

class Road
{

    /**
     * Identifie le controlleur et l'action requise puis exécute
     */
	public function doAction($controlleur, $action)
	{
		// removing tirets(-) in action
		$action = $this->parseActionSeparator($action);
		// check for controlleur exists
		if ($this->findControlleur($controlleur)) {
			include_once ROOT.'controlleurs/'.ucfirst($controlleur).'Controlleur.php';
			$ctrl = "Poki\\" . ucfirst($controlleur).'Controlleur';
			$ctrl = new $ctrl();
			if (method_exists($ctrl, $action)) {
				$ctrl->$action();
			}
			else {
				if (method_exists($ctrl, 'index')) {
					$action = 'index';
					$ctrl->$action();
				}
				else {
					if ( Config::$env == 'DEV' )
						throw new \Exception("Action Introuvable !", 1);
					else
						(new DefaultsControlleur())->home();
				}

			}
		}
		else {
			if ($this->findControlleur('defaults')) {
				include_once ROOT.'controlleurs/DefaultsControlleur.php';
				$ctrl = new \Poki\DefaultsControlleur();
				$action = $this->parseActionSeparator($controlleur);
				if (method_exists($ctrl, $action)) {
					$ctrl->$action();
				}
				else {
					if ( Config::$env == 'DEV' )
						throw new \Exception("Controlleur Introuvable", 1);
					else
						(new DefaultsControlleur())->home();
				}
			}
			else {
				if ( Config::$dev )
					throw new Exception("Controlleur Introuvable", 1);
			}
		}
	}

    /**
     * Recherche le controlleur
     */
	public function findControlleur($controlleur)
	{
		return file_exists(ROOT.'controlleurs/'.ucfirst($controlleur).'Controlleur.php');
	}

    /**
     * Retrouve une action depuis une chaine avec des tirets(-)
     */
	public function parseActionSeparator($action) {
	    $newAction = explode('-', $action);
        for ($i=0; $i<count($newAction); $i++) {
            if ($i>0) {
                $newAction[$i] = ucfirst($newAction[$i]);
            }
        }
        return implode('', $newAction);
    }
}

$road = new Road();
