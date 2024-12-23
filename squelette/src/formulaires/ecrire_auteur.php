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

function formulaires_ecrire_auteur_charger_dist(string $id_auteur, string $id_article, string $mail): array {
	// id du formulaire (pour en avoir plusieurs sur une meme page)
	$id = '_ar' . $id_article;
	if (!empty($id_auteur)) {
		$id = '_' . $id_auteur;
	}
	$email_envoyeur = '';
	if (isset($GLOBALS['visiteur_session']['email'])) {
		$email_envoyeur = $GLOBALS['visiteur_session']['email'];
	}

	return [
		'sujet_message_auteur' => '',
		'texte_message_auteur' => '',
		'email_message_auteur' => $email_envoyeur,
		'nobot' => '',
		'id' => $id,
		'id_auteur' => $id_auteur,
		'id_article' => $id_article,
	];
}

function formulaires_ecrire_auteur_verifier_dist(string $id_auteur, string $id_article, string $mail): array {
	$erreurs = array();
	include_spip('inc/filtres');

	if (!$email_envoyeur = _request('email_message_auteur')) {
		$erreurs['email_message_auteur'] = _T('info_obligatoire');
	} elseif (!email_valide($email_envoyeur)) {
		$erreurs['email_message_auteur'] = _T('form_prop_indiquer_email');
	} else {
		include_spip('inc/session');
		session_set('email', $email_envoyeur);
	}

	if (!$sujet = _request('sujet_message_auteur')) {
		$erreurs['sujet_message_auteur'] = _T('info_obligatoire');
	} elseif (!(strlen($sujet) > 3)) {
		$erreurs['sujet_message_auteur'] = _T('forum:forum_attention_trois_caracteres');
	}

	if (!$texte = _request('texte_message_auteur')) {
		$erreurs['texte_message_auteur'] = _T('info_obligatoire');
	} elseif (!(strlen($texte) > 10)) {
		$erreurs['texte_message_auteur'] = _T('forum:forum_attention_dix_caracteres');
	}

	if (_request('nobot')) {
		$erreurs['message_erreur'] = _T('pass_rien_a_faire_ici');
	}
	// valeur du bouton submit
	if (!_request('confirmer') && count($erreurs) === 0) {
		$erreurs['previsu'] = ' ';
		$erreurs['message_erreur'] = '';
	}

	return $erreurs;
}

function formulaires_ecrire_auteur_traiter_dist(string $id_auteur, string $id_article, string $mail): array {

	$email_envoyeur = _request('email_message_auteur');
	$sujet_form = _request('sujet_message_auteur');
	$site = supprimer_tags(extraire_multi($GLOBALS['meta']['nom_site']));
	$sujet = sprintf('[%s] %s %s', $site, _T('info_message_2'), $sujet_form);
	$texte = _request('texte_message_auteur');
	$texte .= "\n-- " . $email_envoyeur;
	$texte .= "\n\n-- " . _T('envoi_via_le_site') . ' '
		. $site
		. ' (' . $GLOBALS['meta']['adresse_site'] . "/) --\n";
		
	$corps = array(
		'texte' => $texte,
		'repondre_a' => $email_envoyeur,
		'headers' => array(
			"X-Originating-IP: " . $GLOBALS['ip'],
		),
	);
		
	$envoyer_mail = charger_fonction('envoyer_mail', 'inc');
	if ($envoyer_mail($mail, $sujet, $corps)) {
		return ['message_ok' => _T('form_prop_message_envoye')];
	}
	return ['message_erreur' => _T('pass_erreur_probleme_technique')];
}
