<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication module's language file
 *
 * @package 	CodeIgniter
 * @category 	Modules\Language
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

$lang['auth'] = array(

	/**
	 * ----------------------------------------------
	 * Registration page
	 * ----------------------------------------------
	 */
	'register' => array(
		'title' => 'Create account',
		'description' => 'Join %s community',
		'heading' => 'Create new account'
	),

	// Resend activation link
	'resend' => array(
		'title' => 'Renvoi du lien',
		'description' => 'Renvoi du lien d\'activation du compte',
		'heading' => 'Renvoyer le lien'
	),

	// Login page
	'login' => array(
		'title' => 'Connexion',
		'description' => 'Espace membres',
		'heading' => 'Espace membres'
	),

	// Lost password page
	'recover' => array(
		'title' => 'Mot de passe perdu',
		'description' => 'Récupération du mot de passe',
		'heading' => 'Récupérer le mot de passe'
	),

	// Reset password
	'reset' => array(
		'title' => 'Réinitialisation du mot de passe',
		'description' => 'Réinitialisation du mot de passe du compte',
		'heading' => 'Changer le mot de passe'
	),
);
