<?php
/**
 * admin_config3.php
 * Interface permettant à l'administrateur la configuration de certains paramètres généraux (interactivité)
 * Ce script fait partie de l'application GRR
 * Dernière modification : $Date: 2020-09-30 16:56$
 * @author    Laurent Delineau & JeromeB & Yan Naessens
 * @copyright Copyright 2003-2020 Team DEVOME - JeromeB
 * @link      http://www.gnu.org/licenses/licenses.html
 *
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 */

$grr_script_name = "admin_config3.php";

include "../include/admin.inc.php";

$back = '';
if (isset($_SERVER['HTTP_REFERER']))
	$back = htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES);
$_SESSION['chemin_retour'] = "admin_accueil.php";
$day   = date("d");
$month = date("m");
$year  = date("Y");
check_access(6, $back);
$msg = "";
// Automatic mail
if (isset($_GET['automatic_mail']))
{
	if (!Settings::set("automatic_mail", $_GET['automatic_mail']))
	{
		echo get_vocab('automatic_mail_save_err');
		die();
	}
}
//envoyer_email_avec_formulaire
if (isset($_GET['envoyer_email_avec_formulaire']))
{
	if (!Settings::set("envoyer_email_avec_formulaire", $_GET['envoyer_email_avec_formulaire']))
	{
		echo get_vocab('envoyer_email_avec_formulaire_save_err');
		die();
	}
}
// javascript_info_disabled
if (isset($_GET['javascript_info_disabled']))
{
	if (!Settings::set("javascript_info_disabled", $_GET['javascript_info_disabled']))
	{
		echo get_vocab('javascript_info_disabled_save_err');
		die();
	}
}
// javascript_info_admin_disabled
if (isset($_GET['javascript_info_admin_disabled']))
{
	if (!Settings::set("javascript_info_admin_disabled", $_GET['javascript_info_admin_disabled']))
	{
		echo get_vocab('javascript_info_admin_disabled_save_err');
		die();
	}
}
if (isset($_GET['grr_mail_method']))
{
	if (!Settings::set("grr_mail_method", $_GET['grr_mail_method']))
	{
		echo get_vocab('grr_mail_method_save_err');
		die();
	}
}
if (isset($_GET['grr_mail_smtp']))
{
	if (!Settings::set("grr_mail_smtp", $_GET['grr_mail_smtp']))
	{
		echo get_vocab('grr_mail_smtp_save_err');
		die();
	}
}
if (isset($_GET['grr_mail_Username']))
{
	if (!Settings::set("grr_mail_Username", $_GET['grr_mail_Username']))
	{
		echo get_vocab('grr_mail_Username_save_err');
		die();
	}
}
if (isset($_GET['grr_mail_Password']))
{
	if (!Settings::set("grr_mail_Password", $_GET['grr_mail_Password']))
	{
		echo get_vocab('grr_mail_Password_save_err');
		die();
	}
}
if (isset($_GET['grr_mail_from']))
{
	if (!Settings::set("grr_mail_from", $_GET['grr_mail_from']))
	{
		echo get_vocab('grr_mail_from_save_err');
		die();
	}
}
if (isset($_GET['grr_mail_fromname']))
{
	if (!Settings::set("grr_mail_fromname", $_GET['grr_mail_fromname']))
	{
		echo get_vocab('grr_mail_fromname_save_err');
		die();
	}
}
if (isset($_GET['smtp_secure']))
{
	if (!Settings::set("smtp_secure", $_GET['smtp_secure']))
	{
		echo get_vocab('save_err')." smtp_secure !<br />";
		die();
	}
}
if (isset($_GET['smtp_port']))
{
	if (!Settings::set("smtp_port", $_GET['smtp_port']))
	{
		echo get_vocab('smtp_port_save_err');
		die();
	}
}
// Si Email test renseigné on y envoie un mail
if (isset($_GET['mail_test']) && !empty($_GET['mail_test']))
{
    $mail_test = clean_input($_GET['mail_test']);
    if (!validate_email($mail_test)){
        echo get_vocab('invalid_test_mail_address');
        die();
    }
	require_once '../include/mail.class.php';
	require_once '../phpmailer/PHPMailerAutoload.php';
	Email::Envois($mail_test, 'GRR, votre système de réservations', "Ceci est un test depuis l'administration de votre GRR.<br>Le mail est arrivé à destination.", Settings::get('grr_mail_from'), '', '');
}
if (isset($_GET['ok']))
{
	if (isset($_GET['grr_mail_Bcc']))
		$grr_mail_Bcc = "y";
	else
		$grr_mail_Bcc = "n";
	if (!Settings::set("grr_mail_Bcc", $grr_mail_Bcc))
	{
		echo get_vocab('grr_mail_Bcc_save_err');
		die();
	}
}
if (isset($_GET['verif_reservation_auto']))
{
	if (!Settings::set("verif_reservation_auto", $_GET['verif_reservation_auto']))
	{
		echo get_vocab('save_err')." verif_reservation_auto !<br />";
		die();
	}
	if ($_GET['verif_reservation_auto'] == 0)
	{
		$_GET['motdepasse_verif_auto_grr'] = "";
		$_GET['chemin_complet_grr'] = "";
	}
}
if (isset($_GET['motdepasse_verif_auto_grr']))
{
	if (($_GET['verif_reservation_auto'] == 1) && ($_GET['motdepasse_verif_auto_grr'] == ""))
		$msg .= "l'exécution du script verif_auto_grr.php requiert un mot de passe !\\n";
	if (!Settings::set("motdepasse_verif_auto_grr", $_GET['motdepasse_verif_auto_grr']))
	{
		echo $vocab['save_err']." motdepasse_verif_auto_grr !<br />";
		die();
	}
}
if (isset($_GET['chemin_complet_grr']))
{
	if (!Settings::set("chemin_complet_grr", $_GET['chemin_complet_grr']))
	{
		echo $vocab['save_err']." chemin_complet_grr !<br />";
		die();
	}
}
if (!Settings::load())
	die(".get_vocab('error_settings_load').");
# print the page header
start_page_w_header("", "", "", $type="with_session");
if (isset($_GET['ok']))
{
	$msg = get_vocab("message_records");
	affiche_pop_up($msg,"admin");
}
// Affichage de la colonne de gauche
include "admin_col_gauche2.php";
echo '<div class="col-md-9 col-sm-8 col-xs-12">';
echo "<h2>".get_vocab('admin_config3.php')."</h2>";
echo "<form action=\"./admin_config3.php\"  method=\"get\" >\n";
//
// Automatic mail
//********************************
//
echo "<h3>".get_vocab('title_automatic_mail')."</h3>\n";
echo "<p><i>".get_vocab("warning_message_mail")."</i></p>\n";
echo "<p>".get_vocab("explain_automatic_mail")."\n";
?>
<br />
<input type='radio' name='automatic_mail' value='yes' id='label_3' <?php if (Settings::get("automatic_mail") == 'yes') echo "checked=\"checked\"";?> />
<label for='label_3'>
<?php
echo get_vocab("mail_admin_on");
if (Settings::get("automatic_mail") == 'yes')
	echo " - <a href='admin_email_manager.php'>".get_vocab('admin_email_manager.php')."</a>\n";
?>
</label>
<br />
<input type='radio' name='automatic_mail' value='no' id='label_4' <?php if (Settings::get("automatic_mail") == 'no') echo "checked=\"checked\"";?> />
<label for='label_4'><?php echo get_vocab("mail_admin_off"); ?></label>
<?php
// Configuration des liens adresses
echo "</p><hr /><h3>".get_vocab('configuration_liens_adresses')."</h3>\n";
echo "<p>";
?>
<input type='radio' name='envoyer_email_avec_formulaire' value='yes' id='label_5' <?php if (Settings::get("envoyer_email_avec_formulaire") == 'yes') echo "checked=\"checked\"";?> /><label for='label_5'><?php echo get_vocab("envoyer_email_avec_formulaire_oui"); ?></label>
<br /><input type='radio' name='envoyer_email_avec_formulaire' value='no' id='label_6' <?php if (Settings::get("envoyer_email_avec_formulaire") == 'no') echo "checked=\"checked\"";?> /> <label for='label_6'><?php echo get_vocab("envoyer_email_avec_formulaire_non"); ?></label>
<?php
// Paramètres de configuration de l'envoi automatique des mails
echo "</p><hr /><h3>".get_vocab('Parametres configuration envoi automatique mails')."</h3>\n";
echo "<p>".get_vocab('Explications des parametres configuration envoi automatique mails');
// Choix mail ou smtp
echo "<br /><br /><input type=\"radio\" name=\"grr_mail_method\" value=\"mail\" ";
if (Settings::get('grr_mail_method') == "mail")
	echo " checked=\"checked\" ";
echo "/>\n";
echo get_vocab('methode mail');
echo "  <input type=\"radio\" name=\"grr_mail_method\" value=\"smtp\" ";
if (Settings::get('grr_mail_method') == "smtp")
	echo " checked=\"checked\" ";
echo "/>\n";
echo get_vocab('methode smtp');
// Serveur SMTP:
echo "\n<br /><br />".get_vocab('Explications methode smtp 1').get_vocab('deux_points');
echo "\n<input type = \"text\" name=\"grr_mail_smtp\" value =\"".Settings::get('grr_mail_smtp')."\" />";
echo "\n<br />".get_vocab('Explications methode smtp 2');
// Utilisateur SMTP:
echo "\n<br />".get_vocab('utilisateur smtp').get_vocab('deux_points');
echo "\n<input type = \"text\" name=\"grr_mail_Username\" value =\"".Settings::get('grr_mail_Username')."\" />";
// MDP SMTP:
echo "\n<br />".get_vocab('pwd').get_vocab('deux_points');
echo "\n<input type = \"password\" name=\"grr_mail_Password\" value =\"".Settings::get('grr_mail_Password')."\" />";
// @ expediteur:
echo "\n<br />".get_vocab('Email_expediteur_messages_automatiques').get_vocab('deux_points');
if (trim(Settings::get('grr_mail_from')) == "")
	$grr_mail_from = "noreply@mon.site.fr";
else
	$grr_mail_from = Settings::get('grr_mail_from');
echo "\n<input type = \"text\" name=\"grr_mail_from\" value =\"".$grr_mail_from."\" size=\"30\" />";
// Nom expediteur
echo "\n<br />".get_vocab('Nom_expediteur_messages_automatiques').get_vocab('deux_points');
echo "\n<input type = \"text\" name=\"grr_mail_fromname\" value =\"".Settings::get('grr_mail_fromname')."\" size=\"30\" />";
// smtpauth
//echo "\n<br />".get_vocab('smtp_auth').get_vocab('deux_points');
//echo "\n<input type = \"text\" name=\"smtp_auth\" value =\"".Settings::get('smtp_auth')."\" size=\"30\" />";
// smtpsecure
echo "\n<br />".get_vocab('smtp_secure').get_vocab('deux_points');
echo "\n<input type = \"text\" name=\"smtp_secure\" value =\"".Settings::get('smtp_secure')."\" size=\"30\" />";
// Port
echo "\n<br />".get_vocab('smtp_port').get_vocab('deux_points');
echo "\n<input type = \"text\" name=\"smtp_port\" value =\"".Settings::get('smtp_port')."\" size=\"30\" />";
// Mail Test
echo "\n<br />".get_vocab('mail_test').get_vocab('deux_points');
echo "\n<input type = \"email\" name=\"mail_test\" value =\"\" size=\"30\" />";
// Copie CCi
echo "\n<br /><br />";
echo "\n<input type=\"checkbox\" name=\"grr_mail_Bcc\" value=\"y\" ";
if (Settings::get('grr_mail_Bcc') == "y")
	echo " checked=\"checked\" ";
echo "/>";
echo get_vocab('copie cachee');
# Désactive les messages javascript (pop-up) après la création/modificatio/suppression d'une réservation
# 1 = Oui, 0 = Non
echo "\n</p><hr /><h3>".get_vocab("javascript_info_disabled_msg")."</h3>";
echo "\n<table>";
echo "\n<tr><td>".get_vocab("javascript_info_disabled0")."</td><td>";
echo "\n<input type='radio' name='javascript_info_disabled' value='0' ";
if (Settings::get("javascript_info_disabled") == '0')
	echo "checked=\"checked\"";
echo " />";
echo "\n</td></tr>";
echo "\n<tr><td>".get_vocab("javascript_info_disabled1")."</td><td>";
echo "\n<input type='radio' name='javascript_info_disabled' value='1' ";
if (Settings::get("javascript_info_disabled") == '1')
	echo "checked=\"checked\"";
echo " />";
echo "\n</td></tr>";
echo "\n</table>";

# Désactive les messages javascript d'information (pop-up) dans les menus d'administration
# 1 = Oui, 0 = Non
echo "\n<hr /><h3>".get_vocab("javascript_info_admin_disabled_msg")."</h3>";
echo "\n<table>";
echo "\n<tr><td>".get_vocab("javascript_info_admin_disabled0")."</td><td>";
echo "\n<input type='radio' name='javascript_info_admin_disabled' value='0' ";
if (Settings::get("javascript_info_admin_disabled") == '0')
	echo "checked=\"checked\"";
echo " />";
echo "\n</td></tr>";
echo "\n<tr><td>".get_vocab("javascript_info_disabled1")."</td><td>";
echo "\n<input type='radio' name='javascript_info_admin_disabled' value='1' ";
if (Settings::get("javascript_info_admin_disabled") == '1')
	echo "checked=\"checked\"";
echo " />";
echo "\n</td></tr>";
echo "\n</table>";

# tâche automatique de suppression
echo "\n<hr /><h3>".get_vocab("suppression_automatique_des_reservations")."</h3>";
echo "\n<p>".get_vocab('Explications suppression_automatique_des_reservations')."</p>";
echo "\n<table>";
echo "\n<tr><td>".get_vocab("verif_reservation_auto0")."</td><td>";
echo "\n<input type='radio' name='verif_reservation_auto' value='0' ";
if (Settings::get("verif_reservation_auto") == '0')
	echo "checked=\"checked\"";
echo " />";
echo "\n</td></tr>";
echo "\n<tr><td>".get_vocab("verif_reservation_auto1")."</td><td>";
echo "\n<input type='radio' name='verif_reservation_auto' value='1' ";
if (Settings::get("verif_reservation_auto") == '1')
	echo "checked=\"checked\"";
echo " />";
echo "\n</td></tr>";
echo "\n<tr><td>".get_vocab("verif_reservation_auto2").get_vocab("deux_points")."</td><td>";
echo "\n<input type=\"text\" name=\"motdepasse_verif_auto_grr\" value=\"".Settings::get("motdepasse_verif_auto_grr")."\" size=\"20\" />";
echo "\n</td></tr>";
echo "\n<tr><td>".get_vocab("verif_reservation_auto3").get_vocab("deux_points")."</td><td>";
echo "\n<input type=\"text\" name=\"chemin_complet_grr\" value=\"".Settings::get("chemin_complet_grr")."\" size=\"20\" />";
echo "\n</td></tr>";
echo "\n</table>";
echo "\n<p><input type=\"hidden\" name=\"page_config\" value=\"3\" />";
echo "\n<br /></p><div id=\"fixe\" style=\"text-align:center;\"><input class=\"btn btn-primary\" type=\"submit\" name=\"ok\" value=\"".get_vocab("save")."\" style=\"font-variant: small-caps;\"/></div>";
echo "\n</form>";
// fin de l'affichage de la colonne de droite et de la page
echo "</div>";
end_page();
?>