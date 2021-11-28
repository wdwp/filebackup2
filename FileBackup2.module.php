<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: FileBackup2
# An addon module for CMS Made Simple to provide CMSMS full files backup
# tarred and gzipped
#-------------------------------------------------------------------------
# A fork of:
# Module: FFileBackup (c) 2009, blast (blastg@gmail.com) ['wannabe coder']
# Forked by Yuri Haperski (wdwp@ya.ru)
#
#-------------------------------------------------------------------------
#
# CMSMS - CMS Made Simple is (c) 2006 - 2021 by CMS Made Simple Foundation
# CMSMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# Visit the CMSMS Homepage at: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE

class FileBackup2 extends CMSModule
{
	/****** CONFIGURATION *****/

	// Set to true if you want to disable checks on install (default = false)
	var $no_checks_on_install = false;

	// Turns off additional info on execution (default = false)
	var $safe_mode = false;


	/***** DO NOT MODIFY BELOW ****/

	function GetName()
	{
		return 'FileBackup2';
	}

	function GetFriendlyName()
	{
		return $this->Lang('friendlyname');
	}

	function GetVersion()
	{
		return '1.1';
	}

	function MinimumCMSVersion()
	{
		return "2.1";
	}

	function IsPluginModule()
	{
		return false;
	}

	function HasAdmin()
	{
		return true;
	}

	function GetAdminDescription()
	{
		return $this->Lang('admindesc');
	}

	function BackupDirectory()
	{
		// Path where to backups will be created. Default is under /admin (this dir is read from config.php)
		// name = backups/
		global $config;
		return $config['root_path'] . '/' . $config['admin_dir'] . '/' . 'backups';
	}

	function BackupDirectoryUrl()
	{
		// URL of backups files
		global $config;
		return $config['root_url'] . '/' . $config['admin_dir'] . '/' . 'backups';
	}

	function Create_backup_dir()
	{
		if (!is_dir($this->BackupDirectory())) {
			@mkdir($this->BackupDirectory(), 0777, true);
		}
		@chmod(dirname(__FILE__), 0777);
		if (!file_exists($this->BackupDirectory() . '/index.html')) {
			@touch($this->BackupDirectory() . '/index.html');
		}
		@chmod(dirname(__FILE__), 0777);
		if (is_dir($this->BackupDirectory()) || file_exists($this->BackupDirectory() . '/index.html')) {
			return true;
		} else {
			return false;
		}
	}

	function Install()
	{
		$error = false;
		$return = false;
		$this->Tar_binaries_locator();
		if ($this->GetPreference('path_to_tar') != '') {
			$this->CreatePermission('Create FileBackup Archive', 'Create FileBackup Archive');
			$this->CreatePermission('Restore FileBackup Archive', 'Restore FileBackup Archive');

			if (!$this->Create_backup_dir()) {
				$error = '<li>' . $this->Lang('cannot_create_folder') . '</li>';
			}
			$this->SetPreference('tar_backup_options', '-cpzvf');
			$this->SetPreference('tar_restore_options', '--directory / -xpzf');
			$this->SetPreference('first_run', true);
			$this->SetPreference('rmbckdir', '1');

			$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('installed', $this->GetVersion()));
		} else {
			$error =  '<li>' . $this->Lang('Tar_missing') . '</li>';
		}

		if ($error) {
			$return =  '<p style="color:#E41C1C;font-weight:bold;"><img src="../modules/FileBackup2/icons/dialog-error.png" alt="" width="22" height="22" style="margin-bottom:-8px;margin-right:0.5em;" />' . $this->Lang('install_abort') . '</p><ul>' . $error . '</ul><p>' . $this->Lang('make_corrections') . '</p>';
		}

		if ($this->no_checks_on_install) {
			return false;
		} else {
			return $return; // false = sucessful
		}
	}

	function Remove_backup_dir()
	{
		if (is_dir($this->BackupDirectory())) {
			@$this->rmdirr($this->BackupDirectory());
		}
	}
	function rmdirr($dirname)
	{

		/**
		 * Delete a file, or a folder and its contents (recursive algorithm)
		 *
		 * @author      Aidan Lister <aidan@php.net>
		 * @version     1.0.3
		 * @link        http://aidanlister.com/repos/v/function.rmdirr.php
		 * @param       string   $dirname    Directory to delete
		 * @return      bool     Returns TRUE on success, FALSE on failure
		 */
		// Sanity check
		if (!file_exists($dirname)) {
			return false;
		}

		// Simple delete for a file
		if (is_file($dirname) || is_link($dirname)) {
			return unlink($dirname);
		}

		// Loop through the folder
		$dir = dir($dirname);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			// Recurse
			$this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
		}

		// Clean up
		$dir->close();
		return rmdir($dirname);
	}



	function Tar_binaries_locator()
	{
		//tries to locate tar binaries's folder
		switch (strtoupper(substr(PHP_OS, 0, 3))) {
			case 'WIN':
				// not yet supported
				break;
			default:
				$test = trim(ltrim(@exec('whereis -b tar'), 'tar: '));
				$space_pos = stripos($test, ' ');
				if ($space_pos !== false) {
					$test = substr($test, 0, $space_pos);
				}
				if (preg_match('/\/tar/', $test)) {
					$this->SetPreference('path_to_tar', rtrim($test, '/tar'));
				} else {
					$test = trim(ltrim(@exec('which tar'), 'tar: '));
					if (preg_match('/\/tar/', $test)) {
						$this->SetPreference('path_to_tar', rtrim($test, '/tar'));
					} else {
						$this->SetPreference('path_to_tar', '');
						$this->SetPreference('first_run', true);
					}
				}
				break;
		}
	}

	function InstallPostMessage()
	{
		return $this->Lang('postinstall', '<img src="../modules/FileBackup2/icons/document-save-as.png" alt="" width="32" height="32" style="margin-bottom:-8px;margin-right:0.5em;" />');
	}

	function Upgrade($oldversion, $newversion)
	{
		global $config;
		$current_version = $oldversion;
		switch ($current_version) {
			case "0.1":
			case "0.2": {
					@$this->rmdirr($config['root_path'] . '/' . $config['admin_dir'] . '/' . 'backups/tgz');
					@touch($this->BackupDirectory() . '/index.html');
					$this->SetPreference('rmbckdir', '1');
					$current_version = "0.5";
				}
			case "0.3":
			case "0.4": {
					$this->SetPreference('rmbckdir', '1');
					$current_version = "0.5";
				}
			case "0.5":
				break;
		}
	}

	function Uninstall()
	{
		$this->RemovePermission('Create FileBackup Archive', 'Create FileBackup Archive');
		$this->RemovePermission('Restore FileBackup Archive', 'Restore FileBackup Archive');
		$this->RemovePreference('path_to_tar');
		$this->RemovePreference('tar_backup_options');
		$this->RemovePreference('tar_restore_options');
		$this->RemovePreference('tar');
		$this->RemovePreference('first_run');
		if ($this->GetPreference("rmbckdir") == "1") {
			$this->Remove_backup_dir();
		}
		$this->RemovePreference("rmbckdir");
		$this->Audit(0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
	}

	function GetAdminSection()
	{
		return 'siteadmin';
	}

	function VisibleToAdminUser()
	{
		return $this->CheckPermission('Create FileBackup Archive');
	}

	function GetHelp()
	{
		return $this->Lang('helptext');
	}

	function GetAuthor()
	{
		return 'blast (blastg@gmail.com) [wannabe coder]';
	}

	function GetChangeLog()
	{
		return $this->Lang('changelog');
	}

	function showForm($id, $params, $returnid)
	{
		global $gCms;
		if ($this->GetPreference('first_run')) {
			echo '<p style="color:#F57900;"><img src="../modules/FileBackup2/icons/emblem-important.png" alt="" width="32" height="32" style="margin-bottom:-8px;margin-right:0.5em;" />' . $this->Lang('first_run') . '</p>';
			$params['active_tab'] = 'options';
		}
		echo $this->StartTabHeaders();
		$tab = (empty($params['active_tab'])) ? '' : $params['active_tab'];
		echo $this->SetTabHeader('dump', $this->Lang('FORM_Backup_Database'), ($tab == 'dump'));
		echo $this->SetTabHeader('restore', $this->Lang('FORM_Restore_Database'), ($tab == 'restore'));
		echo $this->SetTabHeader('delete', $this->Lang('FORM_Delete_Dataset'), ($tab == 'delete'));
		echo $this->SetTabHeader('options', $this->Lang('FORM_options'), ($tab == 'options'));
		echo $this->EndTabHeaders();

		echo $this->StartTabContent();

		echo $this->StartTab('dump');
		$this->smarty->assign('formstart', $this->CreateFormStart($id, 'dumpdatabase', $returnid));


		$hidden = $this->CreateInputHidden($id, 'path', $this->GetPreference('path_to_tar'));
		$hidden .= $this->CreateInputHidden($id, 'bck_options', $this->GetPreference('tar_backup_options'));
		$this->smarty->assign('FORM_hidden', $hidden);
		$this->smarty->assign('FORM_Please_wait', $this->Lang('FORM_Please_wait'));
		$this->smarty->assign('FORM_May_take', $this->Lang('FORM_May_take'));
		$this->smarty->assign('FORM_title', $this->Lang('FORM_title'));
		$this->smarty->assign('FORM_filename', $this->Lang('FORM_filename'));
		$this->smarty->assign('FORM_input_filename', $this->CreateInputText($id, 'filename', 'filebackup-' . date('Y-m-d_H-i-s') . '.tgz', 35));
		$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'backup', $this->Lang('FORM_Backup_Database')));
		$this->smarty->assign('formend', $this->CreateFormEnd());
		echo $this->ProcessTemplate('DumpForm.tpl');
		echo $this->EndTab();

		echo $this->StartTab('restore');
		$this->smarty->assign('formstart', $this->CreateFormStart($id, 'restoredatabase', $returnid));
		$hidden = $this->CreateInputHidden($id, 'path', $this->GetPreference('path_to_tar'));
		$hidden .= $this->CreateInputHidden($id, 'options', $this->GetPreference('tar_restore_options'));
		$this->smarty->assign('FORM_hidden', $hidden);
		$this->smarty->assign('FORM_Restore_Database', $this->Lang('FORM_Restore_Database'));
		$this->smarty->assign('FORM_Warning_Restore', $this->Lang('FORM_Warning_Restore'));
		$this->smarty->assign('FORM_filename', $this->Lang('FORM_filename'));
		$this->smarty->assign('FORM_input_filename', $this->CreateInputDropdown($id, 'filename', $this->ListBackupSets()));
		$AreYouSure = 'onclick="return confirm(\'' . $this->Lang('FORM_Warning_Restore') . '\n\n' . $this->Lang('AreYouSure') . '\');"';
		$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'restore', $this->Lang('FORM_Restore'), $AreYouSure));
		$this->smarty->assign('formend', $this->CreateFormEnd());
		echo $this->ProcessTemplate('RestoreForm.tpl');
		echo $this->EndTab();

		echo $this->StartTab('delete');
		$this->smarty->assign('formstart', $this->CreateFormStart($id, 'deletedataset', $returnid));
		$this->smarty->assign('FORM_Delete_Dataset', $this->Lang('FORM_Delete_Dataset'));
		$this->smarty->assign('FORM_filename', $this->Lang('FORM_filename'));
		$files = $this->ListBackupSets();
		$this->smarty->assign('FORM_input_filename', $this->CreateInputDropdown($id, 'filename', $files));
		$AreYouSure = 'onclick="return confirm(\'' . $this->Lang('FORM_Warning_Delete') . '\n\n' . $this->Lang('AreYouSure') . '\');"';
		$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'delete', $this->Lang('FORM_Delete'), $AreYouSure));
		$this->smarty->assign('formend', $this->CreateFormEnd());
		echo $this->ProcessTemplate('DeleteForm.tpl');
		echo $this->EndTab();

		echo $this->StartTab('options');
		$this->smarty->assign('formstart', $this->CreateFormStart($id, 'saveoptions', $returnid));
		$this->smarty->assign('FORM_options', $this->Lang('FORM_options'));
		$this->smarty->assign('FORM_path_to_tar', $this->Lang('FORM_path_to_tar'));
		$this->smarty->assign('FORM_path_to_tar_help', $this->Lang('FORM_path_to_tar_help'));
		$this->smarty->assign('FORM_input_path', $this->CreateInputText($id, 'path', $this->GetPreference('path_to_tar'), 35));
		$this->smarty->assign('FORM_input_options', $this->CreateInputText($id, 'options', $this->GetPreference('tar_backup_options'), 80));
		$this->smarty->assign('FORM_parameters', $this->Lang('FORM_parameters'));
		$this->smarty->assign('FORM_parameters_explained', $this->Lang('FORM_parameters_explained'));
		$this->smarty->assign('FORM_parameters_help', $this->Lang('FORM_parameters_help'));
		$this->smarty->assign('FORM_input_restore', $this->CreateInputText($id, 'restore', $this->GetPreference('tar_restore_options'), 80));
		$this->smarty->assign('FORM_del_bckdir_on_uninstall', $this->Lang('FORM_del_bckdir_on_uninstall'));
		$this->smarty->assign('FORM_input_del_bckdir_on_uninstall', $this->CreateInputCheckbox($id, 'rmbckdir', '1', $this->GetPreference('rmbckdir')));

		$this->smarty->assign('FORM_tables', $this->Lang('FORM_tables'));

		$this->smarty->assign('FORM_restore_parameters', $this->Lang('FORM_restore_parameters'));
		$this->smarty->assign('FORM_restore_help', $this->Lang('FORM_restore_help'));
		$this->smarty->assign('FORM_save', $this->CreateInputSubmit($id, 'saveoptions', $this->Lang('FORM_save')));
		$this->smarty->assign('formend', $this->CreateFormEnd());
		echo $this->ProcessTemplate('OptionsForm.tpl');
		echo $this->EndTab();
	}

	function ListBackupSets()
	{
		$backupSets = array();
		$dir = opendir($this->BackupDirectory());
		while ($thisFile = readdir($dir)) {
			if ($thisFile[0] != ".") {
				if ((is_file($this->BackupDirectory() . "/" . $thisFile)) && ($thisFile != 'index.html') && (strpos($thisFile, '.tgz', 1))) {
					$backupSets[$thisFile] = $this->BackupDirectory() . "/" . "$thisFile";
				}
			}
		}
		closedir($dir);
		arsort($backupSets);
		return $backupSets;
	}

	function CheckAccess($permission = 'Create FileBackup Archive')
	{
		$access = $this->CheckPermission($permission);
		if (!$access) {
			echo '<p style="color:#E41C1C;font-weight:bold;"><img src="../modules/FileBackup2/icons/dialog-error.png" alt="" width="22" height="22" style="margin-bottom:-8px;margin-right:0.5em;" />' . $this->Lang('needpermission', $permission) . '</p>';
			return false;
		} else {
			return true;
		}
	}

	function DoAction($action, $id, $params, $returnid = -1)
	{
		$db = $this->cms->db;
		switch ($action) {
			case 'default':
				break;
			case 'defaultadmin':
				$this->showForm($id, $params, $returnid);
				break;
			case 'dumpdatabase':
				if ($this->CheckAccess('Create FileBackup Archive')) {
					$this->doDump($id, $params, $returnid);
					$this->ReturnAdmin($id, $params, $returnid);
				}
				break;
			case 'saveoptions':
				if ($this->CheckAccess('Create FileBackup Archive')) {
					$this->doSaveOptions($id, $params, $returnid);
				}
				break;
			case 'restoredatabase':
				if ($this->CheckAccess('Restore FileBackup Archive')) {
					$this->doRestore($id, $params, $returnid);
					$this->ReturnAdmin($id, $params, $returnid);
				}
				break;
			case 'deletedataset':
				if ($this->CheckAccess('Restore FileBackup Archive')) {
					if (unlink($params['filename'])) {
						$params = array('module_message' => $this->Lang('DELETE_Deleted', basename($params['filename'])), 'active_tab' => 'delete');
					} else {
						$params = array('module_error' => $this->Lang('DELETE_Error', basename($params['filename'])), 'active_tab' => 'delete');
					}
					$this->Redirect($id, 'defaultadmin', $returnid, $params);
				}
				break;
		}
	}

	function doDump($id, $params, $returnid)
	{
		$config = &$this->GetConfig();
		$d = escapeshellcmd($params['filename']);
		$options = escapeshellcmd($params['bck_options']);
		$output_file = $this->BackupDirectory() . '/' . $d;
		$executable = escapeshellarg($params['path'] . '/tar');

		$command = sprintf(
			'%s %s %s %s --exclude=\'%s\'',
			$executable,
			$options,
			$output_file,
			$this->config['root_path'],
			$this->BackupDirectory()
		);

		echo '<h2><img src="../modules/FileBackup2/icons/utilities-terminal.png" alt="" width="22" height="22" style="margin-bottom:-5px;margin-right:0.25em;" />' . $this->Lang('DUMP_Task_started') . '</h2>';

		echo $this->Lang('DUMP_Executing', $command);
		echo '<p><b>' . $this->Lang('Output') . '</b></p>';

		if (file_exists($output_file)) unlink($output_file);
		touch($output_file);

		echo $command;
		exec($command, $output_array, $retval);
		foreach ($output_array as $line) {
			echo $line . "<br>";
		}

		echo '</pre>';

		echo '<h2 style="color:green"><img src="../modules/FileBackup2/icons/document-save-as.png" alt="" width="32" height="32" style="margin-bottom:-8px;margin-right:0.5em;" />' . $this->Lang('DUMP_Task_completed') . '</h2>';

		if (filesize($output_file) > 1023) {
			echo sprintf('<p><a href="%s"><b>%s</b></a>... %s%s</p>', $this->BackupDirectoryUrl() . '/' . $d, $d, $this->suffix_bytes(filesize($output_file)), $this->Lang('written_to_disk'));
		} else {
			echo '<pre>';
			readfile($output_file);
			echo $this->Lang('DUMP_Filesize', $this->suffix_bytes(filesize($output_file)));
			echo '</pre>';
			unlink($output_file);
		}

		if (!file_exists($output_file)) {
			echo '<p style="color:#E41C1C;font-weight:bold;"><img src="../modules/FileBackup2/icons/dialog-error.png" alt="" width="22" height="22" style="margin-bottom:-8px;margin-right:0.5em;" />' . $this->Lang('Missing', $output_file) . '</p>';
		}
	}

	function doRestore($id, $params, $returnid)
	{
		$config = &$this->GetConfig();

		$options = escapeshellcmd($params['options']);
		$executable = escapeshellarg($params['path'] . '/tar');
		$oldpath = $this->GetPreference('path_to_tar');
		$oldoptions = $this->GetPreference('tar_backup_options');
		$oldrestore =	$this->GetPreference('tar_restore_options');


		$command = sprintf(
			'%s %s %s',
			$executable,
			$options,
			$params['filename']
		);

		echo '<h2 style="color:#F57900" ><img src="../modules/FileBackup2/icons/emblem-important.png" alt="" width="32" height="32" style="margin-bottom:-8px;margin-right:0.5em;" /> ' . $this->Lang('DUMP_Please_wait') . '</h2><p> ' . $this->Lang('DUMP_May_take') . ' </p> ';

		echo '<h2><img src="../modules/FileBackup2/icons/utilities-terminal.png" alt="" width="22" height="22" style="margin-bottom:-5px;margin-right:0.25em;" /> ' . $this->Lang('DUMP_Task_started') . '</h2>';

		echo $this->Lang('DUMP_Executing', $command);


		echo '<pre>';
		flush();
		system($command, $retval);
		flush();
		echo '</pre>';

		$this->SetPreference('path_to_tar', $oldpath);
		$this->SetPreference('tar_backup_options', $oldoptions);
		$this->SetPreference('tar_restore_options', $oldrestore);

		echo '<h2 style="color:green">' . $this->Lang('DUMP_Task_completed') . '</h2>';
		echo '<p><img src="../modules/FileBackup2/icons/edit-undo.png" alt="" width="32" height="32" style="margin-bottom:-8px;margin-right:0.5em;" />' . $this->Lang('RESTORE_Restored', basename($params['filename'])) . '</p>';

		cms_content_cache::clear();
		$this->Audit(0, $this->Lang('friendlyname') . ' ' . $this->GetVersion(), $this->Lang('RESTORE_Restored', basename($params['filename'])));
	}

	function suffix_bytes($size)
	{
		$count = 0;
		$format = array('', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
		while (($size / 1024) > 1 && $count < 8) {
			$size = $size / 1024;
			$count++;
		}
		$return = number_format($size, 0, '', '.') . " " . $format[$count];
		return $return;
	}

	function doSaveOptions($id, $params, $returnid)
	{
		$cleanup_reg = '/(^[^-]+| [^-{1,2}][^ ]+)/i';

		$path = rtrim($params['path'], '/ \\');
		$options = trim(preg_replace($cleanup_reg, ' ', $params['options']));
		$restore = trim(preg_replace($cleanup_reg, ' ', $params['restore']));

		$this->SetPreference('path_to_tar', $path);
		$this->SetPreference('tar_backup_options', $options);
		$this->SetPreference('tar_restore_options', $restore);


		$rmbckdir = $params['rmbckdir'] ? $params['rmbckdir'] : 0;
		if ($rmbckdir) {
			$this->SetPreference('rmbckdir', 1);
		} else {
			$this->SetPreference('rmbckdir', 0);
		}

		//$this->SetPreference('rmbckdir',$rmbckdir);

		$test = @exec(escapeshellarg($params['path'] . '/tar') . ' --version');
		if (preg_match('/Written by/i', $test)) {
			$this->RemovePreference('first_run');
			$params = array('module_message' => $this->Lang('SAVE_Settings_saved'), 'active_tab' => 'options');
		} else {
			$params = array('module_error' => $this->Lang('SAVE_not_found') . " " . $test, 'active_tab' => 'options');
			$this->SetPreference('first_run', true);
		}
		$this->Audit(0, $this->Lang('friendlyname') . ' ' . $this->GetVersion(), $this->Lang('SAVE_Settings_saved'));
		$this->Redirect($id, 'defaultadmin', $returnid, $params);
	}

	function ReturnAdmin($id, $params, $returnid)
	{
		echo $this->CreateFormStart($id, 'defaultadmin', $returnid);
		echo $this->CreateInputSubmit($id, 'return', $this->Lang('Return'));
		echo $this->CreateFormEnd();
	}
} //class
