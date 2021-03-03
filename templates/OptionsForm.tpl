{$formstart}
	<h2><img src="../modules/FileBackup2/icons/preferences-desktop.png" alt="" width="32" height="32" style="margin-bottom:-8px;margin-right:0.5em;" />{$FORM_options}</h2>

	<table cellspacing="10">

		<tr valign="top"><td><b>{$FORM_path_to_tar}</b>:</td><td>{$FORM_input_path}<br />{$FORM_pathtotar_help}</td></tr>

		<tr valign="top"><td><b>{$FORM_parameters}</b>:</td><td><p>{$FORM_input_options}</p><p>{$FORM_running}</p><blockquote><dl>{$FORM_parameters_explained}</dl></blockquote><p>{$FORM_parameters_help}</p></td></tr>

		<tr valign="top"><td><b>{$FORM_restore_parameters}</b>:</td><td><p>{$FORM_input_restore}</p>
		  {$FORM_parameters_help}</td>
		</tr>

		<tr valign="top"><td><b>{$FORM_del_bckdir_on_uninstall}</b>:</td><td><p>{$FORM_input_del_bckdir_on_uninstall}</p>
		  </td>
		</tr>

		<tr><td></td><td>{$FORM_save}</td></tr>
	</table>

{$formend}
