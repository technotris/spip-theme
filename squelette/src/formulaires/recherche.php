<?php

/***************************************************************************\
 *  SPIP, Système de publication pour l'internet                           *
 *                                                                         *
 *  Copyright © avec tendresse depuis 2001                                 *
 *  Arnaud Martin, Antoine Pitrou, Philippe Rivière, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribué sous licence GNU/GPL.     *
\***************************************************************************/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * chargement des valeurs par defaut des champs du #FORMULAIRE_RECHERCHE
 * on peut lui passer l'url de destination en premier argument
 * on peut passer une deuxième chaine qui va différencier le formulaire pour pouvoir en utiliser plusieurs sur une même page
 *
 * @param string $lien URL où amène le formulaire validé
 * @param string $class Une class différenciant le formulaire
 * @return array
 */
function formulaires_recherche_charger_dist(string $lien = '', string $class = ''): array {
	$id = 'recherche';
	$lang = '';
	$action = generer_url_public('recherche');
	if ($GLOBALS['spip_lang'] !== $GLOBALS['meta']['langue_site']) {
		$lang = $GLOBALS['spip_lang'];
	}
	// action specifique, ne passe pas par Verifier, ni Traiter
	if (!empty($lien)) {
		$action = $lien;
	}
	if (!empty($class)) {
		$id = substr(md5($action . $class), 0, 4);
	}
	return [
		'action' => $action,
		'recherche' => _request('recherche'),
		'lang' => $lang,
		'class' => $class,
		'_id_champ' => $id
	];
}
