<?php
    //META
    $lang['friendlyname'] = 'FileBackup';
    $lang['admindesc'] = 'Runs the external "tar" command to back up the content of the CMSMS files tree into a tgz file.';
    $lang['helptext'] = '
        <h3>What does this do?</h3>
        <ul>
			<li>This module provides an interface to backup the entire CMSMS files tree using <a href="http://www.gnu.org/software/tar/" target="_blank">tar</a> command included in most linux distributions.</li>
			<li>It can restore entire CMSMS files tree to the state at the time the backups was made.</li>
			<li>You can <b>clone</b> a CMSMS server simply executing this procedure:
			<ol>
				<li>Use <a href="http://dev.cmsmadesimple.org/projects/mysqldump">Mysql Dump</a> module to backup your SQL db</li>
				<li>Use <a href="http://dev.cmsmadesimple.org/projects/filebackup">FileBackup</a> to save entire site (included SQL db).</li>
				<li>Reistall CMSMS to a new server</li>
				<li>Install <a href="http://dev.cmsmadesimple.org/projects/filemanager/">FileManager</a> module.</li>
				<li>Install <a href="http://dev.cmsmadesimple.org/projects/filebackup">FileBackup</a> module.</li>
				<li>Upload your tgz backup in admin/backups/tgz with <a href="http://dev.cmsmadesimple.org/projects/filemanager/">FileManager</a>.</li>
				<li>Restore files with <a href="http://dev.cmsmadesimple.org/projects/filebackup">FileBackup</a>.</li>
				<li>Restore SQL db with <a href="http://dev.cmsmadesimple.org/projects/mysqldump">Mysql Dump</a>.</li>
				<li>That\'s all</li>
			</ol>
			<li>No warranty provided. Use at your own risk. Try first on test enviroment. This is (and maybe will be forever) a BETA version!!! .</li>
			<li>Please report any bugs, or simply your general impression to blastg@gmail.com</li>
        </ul>
        <h3 style="color: #FF0000"> WARNING: don\'t forget to delete backup files after download!
         Protect also your backup directory from unwanted download. Forgetting files inside backup folder could be a security breach.</h3>
        <h3>How do I use it?</h3>
        <ol>
			<li>The module needs to be configured first. See the "Preferences" tab, and ajust accordingly to your set up.</li>
			<li>Then, simply enter a filename, and click on "Backup". Your file will be created in the admin/backups directory.</li>
			<li>To restore a dataset, select a dump file and click on "Restore". The CMS files will be overwritten to the state the dump was made.</li>
        </ol>';
    $lang['changelog'] = '<ul>
			<li>Version 0.1 - July 2007. Initial release.</li>
			<li>Version 0.2 - April 2008. Directory creation Bug Fixed.</li>
      <li>Version 0.3 - June 2009. Bugs Fixed (#3564,#3566,#3567) and some little improvements (delete backup directory after uninstall module).</li>
      <li>Version 0.4 - July 2009. Bug fixed #3610</li>
      <li>Version 0.5 - July 2009. Bug fixed #3729 - Added option to delete backup dir after unininstall </li>
        </ul>';

    //INTERFACE
    $lang['Return'] = 'Return';
    $lang['done'] = 'done';
    $lang['written_to_disk'] = 'b written to disk';
    $lang['AreYouSure'] = 'Do you want to proceed anyway?';
    $lang['Output'] = 'Output...';
    $lang['Missing'] = 'ERROR : %s MISSING or TOO SMALL';
    $lang['first_run'] = 'Please check and save the <strong>path to tar binaries</strong> before using FileBackup.';

    //INSTALL & UNINSTALL
    $lang['postinstall'] = '<h3>%sThe FileBackup Module has been installed.</h3><ul><li>Please add <b>Create FileBackup Archive</b> and <b>Restore FileBackup Archive</b> permissions to the appropriate groups.</li></ul>';
    $lang['uninstalled'] = 'Module uninstalled.';
    $lang['installed'] = 'Module version %s installed.';
    $lang['needpermission'] = 'You need %s set to do that.';
    $lang['install_abort'] = 'INSTALLATION ABORTED';
    $lang['make_corrections'] = 'Please make the necessary corrections before trying install again.';
    $lang['Tar_missing'] = 'You cannot use this module, since you don\'t have tar command installed on your server.';
    $lang['cannot_create_folder'] = 'Cannot create admin/backups folder. Make sure the admin folder has write and read permisions granted (chmod 0766).';
    $lang['cannot_create_verbose'] = 'The module cannot write data in his own folder. Please grant write permission (chmod 0766) in modules/FileBackup folder.';

    //DODUMP
    $lang['DUMP_Please_wait'] = 'Please wait until task is fully completed';
    $lang['DUMP_May_take'] = 'May take a few minutes with large sites.';

    $lang['DUMP_Task_started'] = 'Task started';
    $lang['DUMP_Executing'] = '<p><b>Executing...</b></p><p><code>%s</code></p>';
    $lang['DUMP_Task_completed'] = 'Task completed';
    $lang['DUMP_not_readable'] = 'WARNING : cannot find/read the output file (%s). Grant read/write permission (chmod 0766) in module\'s folder';
    $lang['DUMP_not_writable'] = 'ERROR : %s CANNOT BE WRITTEN to backup folder';
    $lang['DUMP_error_tables'] = 'Error fetching table names';
    $lang['DUMP_prefix'] = 'ALL';
    $lang['DUMP_Filesize'] = '-- Filesize : %sbytes';

    //DOSAVE
    $lang['SAVE_Settings_saved'] = 'Preferences have been updated.';
    $lang['SAVE_not_found'] = 'Invalid path to tar utilities';

    //DORESTORE
    $lang['RESTORE_Restored'] = '%s restored';

    //DODELETE
    $lang['DELETE_Deleted'] = '%s has been deleted';
    $lang['DELETE_Error'] = 'Unable to delete <b>%s</b>';

    //FORM
    $lang['FORM_title'] = 'Site backup with <a href="http://www.gnu.org/software/tar/" target="_blank">tar</a> command';
    $lang['FORM_filename'] = 'Dump file name';
    $lang['FORM_options'] = 'Preferences';

    $lang['FORM_path_to_tar'] = 'Path to tar binaries';
    $lang['FORM_del_bckdir_on_uninstall'] = 'Delete backup directory on uninstall';
    $lang['FORM_path_to_tar_help'] = '<ul><li>On most UNIX servers, can be left empty or set to <code>/bin</code></li></ul>';
    $lang['FORM_save'] = 'Save';
    $lang['FORM_Restore'] = 'Restore';
    $lang['FORM_Restore_Database'] = 'Restore Files';
    $lang['FORM_Warning_Restore'] = 'Restoring an archive will overwrite all the current files!';



    $lang['FORM_Please_wait'] = 'Please wait until task is fully completed';
    $lang['FORM_May_take'] = 'May take some minutes with large sites. Please don\'t stop process or reload page.';



	$lang['FORM_Backup_Database'] = 'Backup Files';
    $lang['FORM_parameters'] = 'Tar backup parameters';
    $lang['FORM_parameters_explained'] = '<dt>--cpzvf</dt><dd>Produce .tgz file that can be restored into server quickly. <em>(strongly recommended)</em></dd>';
    $lang['FORM_parameters_help'] ='<a href="http://www.gnu.org/software/tar/" target="_blank">More about tar parameters...</a>';
    $lang['FORM_restore_parameters'] = 'Tar restore parameters';

    $lang['FORM_Delete_Dataset'] = 'Delete Dataset';
    $lang['FORM_Warning_Delete'] = 'WARNING: Once deleted, a dataset cannot be revived.';
    $lang['FORM_Delete'] = 'Delete';

?>
