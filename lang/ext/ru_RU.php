<?php
    //META
    $lang['friendlyname'] = 'Архив сайта';
    $lang['admindesc'] = 'Используется системная команда tar для архивирования файлов сайта в tgz файл.';
    $lang['helptext'] = '
        <h3>Для чего это нужно?</h3>
        <ul>
			<li>Данный модуль позволяет создать архив сайта используя <a href="http://www.gnu.org/software/tar/" target="_blank">tar</a> команду операционной системы linux.</li>
			<li>Также данный модуль можно использовать для восстановления файлов сайта из архива.</li>
			<li>Вы можете <b>сделать архив</b> всего сайта следующими образом:
			<ol>
				<li>Используйте <a href="http://dev.cmsmadesimple.org/projects/mysqldump">Mysql Dump</a> для создания архива базы данных сайта</li>
				<li>Используйте <a href="http://dev.cmsmadesimple.org/projects/filebackup">FileBackup</a> чтобы сохранить файлы сайта.</li>
				<li>Перенос CMSMS на новый сервер</li>
				<li>Установите <a href="http://dev.cmsmadesimple.org/projects/filemanager/">FileManager</a> модуль.</li>
				<li>Установите <a href="http://dev.cmsmadesimple.org/projects/filebackup">FileBackup</a> модуль.</li>
				<li>Загрузите ваш tgz архив в admin/backups/tgz с помощью <a href="http://dev.cmsmadesimple.org/projects/filemanager/">FileManager</a>.</li>
				<li>Восстановите файлы с помощью <a href="http://dev.cmsmadesimple.org/projects/filebackup">FileBackup</a>.</li>
				<li>Восстановите базу данных с помощью <a href="http://dev.cmsmadesimple.org/projects/mysqldump">Mysql Dump</a>.</li>
				<li>Ваш сайт восстановлен.</li>
			</ol>
			<li>Ни каких гарантий не предоставляется. Вы используете данный модуль на свой страх и риск.</li>
			<li>Пожалуйста, сообщайте о всех найденных ошибках на blastg@gmail.com</li>
        </ul>
        <h3 style="color: #FF0000"> ВНИМАНИЕ: не храните архивы сайта на сервере. Также защищайте директорию с архивами от нежелательного
	доступа.</h3>
        <h3>Как использовать модуль?</h3>
        <ol>
			<li>Сперва надо настроить модуль согласно конфигурации сервера. Смотрите раздел "Настройки".</li>
			<li>Затем просто введите имя файла и нажмите кнопку "Архивировать". Ваш архив будет создан в директории admin/backups.</li>
			<li>Для восстановления данных выберите архив и нажмите кнопку "Восстановить".</li>
        </ol>';
    $lang['changelog'] = '<ul>
			<li>Version 0.1 - July 2007. Initial release.</li>
			<li>Version 0.2 - April 2008. Directory creation Bug Fixed.</li>
      <li>Version 0.3 - June 2009. Bugs Fixed (#3564,#3566,#3567) and some little improvements (delete backup directory after uninstall module).</li>
      <li>Version 0.4 - July 2009. Bug fixed #3610</li>
      <li>Version 0.5 - July 2009. Bug fixed #3729 - Added option to delete backup dir after unininstall </li>
        </ul>';

    //INTERFACE
    $lang['Return'] = 'Возврат';
    $lang['done'] = 'сделано';
    $lang['written_to_disk'] = 'записано на диск';
    $lang['AreYouSure'] = 'Вы хотите продолжить?';
    $lang['Output'] = 'Вывод...';
    $lang['Missing'] = 'ОШИБКА : %s ОТСУТСТВУЕТ или ОЧЕНЬ МАЛЕНЬКИЙ';
    $lang['first_run'] = 'Проверьте и сохраните <strong>путь к файлу tar</strong> до архивирования.';

    //INSTALL & UNINSTALL
    $lang['postinstall'] = '<h3>%sFileBackup модуль успешно установлен.</h3><ul><li>Пожалуйста добавьте права на <b>Создать архив</b> и <b>Восстановить сайт</b> в соответствующей группе.</li></ul>';
    $lang['uninstalled'] = 'Модуль установлен.';
    $lang['installed'] = 'Версия модуля %s установлена.';
    $lang['needpermission'] = 'Вам нужно право %s чтобы сделать это.';
    $lang['install_abort'] = 'УСТАНОВКА ПРЕРВАНА';
    $lang['make_corrections'] = 'Пожалуйста, устраните возможные проблемы прежде чем повторить установку.';
    $lang['Tar_missing'] = 'Вы не можете использовать данный модуль, т.к. файл tar отсутствует на вашем сервере.';
    $lang['cannot_create_folder'] = 'Не могу создать директорию admin/backups. Проверьте права на запись в директории admin. Они должны быть 0766.';
    $lang['cannot_create_verbose'] = 'Модуль не может сохранять данные в своей директории. Проверьте права на запись в директории modules/FileBackup. Они должны быть 0766.';

    //DODUMP
    $lang['DUMP_Please_wait'] = 'Подождите, когда работа скрипта будте закончена';
    $lang['DUMP_May_take'] = 'Это может занять некоторое время для больших сайтов.';

    $lang['DUMP_Task_started'] = 'Задача запущена';
    $lang['DUMP_Executing'] = '<p><b>Выполнение...</b></p><p><code>%s</code></p>';
    $lang['DUMP_Task_completed'] = 'Задача завершена';
    $lang['DUMP_not_readable'] = 'ВНИМАНИЕ : нет возможности найти/прочитать файл (%s). Установите права на чтение/запись (chmod 0766) в директории модуля.';
    $lang['DUMP_not_writable'] = 'ОШИБКА : %s не может быть записан в директорию для архива';
    $lang['DUMP_error_tables'] = 'Ошибка получения имен таблиц';
    $lang['DUMP_prefix'] = 'Все';
    $lang['DUMP_Filesize'] = '-- Размер файла : %sbytes';

    //DOSAVE
    $lang['SAVE_Settings_saved'] = 'Настройки были обновлены.';
    $lang['SAVE_not_found'] = 'Неправильный путь к tar';

    //DORESTORE
    $lang['RESTORE_Restored'] = '%s восстановлен';

    //DODELETE
    $lang['DELETE_Deleted'] = '%s был удалён';
    $lang['DELETE_Error'] = 'Нет возможности удалить <b>%s</b>';

    //FORM
    $lang['FORM_title'] = 'Архивирование сайта с помощью команды <a href="http://www.gnu.org/software/tar/" target="_blank">tar</a>';
    $lang['FORM_filename'] = 'Имя архива';
    $lang['FORM_options'] = 'Настройки';

    $lang['FORM_path_to_tar'] = 'Путь к файлу tar';
    $lang['FORM_del_bckdir_on_uninstall'] = 'Удалить директорию архива при удалении модуля';
    $lang['FORM_path_to_tar_help'] = '<ul><li>На большинстве UNIX серверов нужно оставить пустым или указать <code>/bin</code></li></ul>';
    $lang['FORM_save'] = 'Сохранить';
    $lang['FORM_Restore'] = 'Восстановить';
    $lang['FORM_Restore_Database'] = 'Восстановить сайт';
    $lang['FORM_Warning_Restore'] = 'Восстановление из архива заменит существующие файлы!';



    $lang['FORM_Please_wait'] = 'Подождите окончания работы скрипта';
    $lang['FORM_May_take'] = 'Может занять некоторое время на больших сайтах. Не останавливайте процесс и не перегружайте страницу.';


	 $lang['FORM_Backup_Database'] = 'Создать архив';
    $lang['FORM_parameters'] = 'Параметры команды tar';
    $lang['FORM_parameters_explained'] = '<dt>--cpzvf</dt><dd>Создает .tgz архив, который может быть быстро восстановлен. <em>(рекомендуется)</em></dd>';
    $lang['FORM_parameters_help'] ='<a href="http://www.gnu.org/software/tar/" target="_blank">Больше о параметрах tar...</a>';
    $lang['FORM_restore_parameters'] = 'Параметры tar для восстановления';

    $lang['FORM_Delete_Dataset'] = 'Удалить архив';
    $lang['FORM_Warning_Delete'] = 'ВНИМАНИЕ: Удалённые архивы не могут быть восстановлены.';
    $lang['FORM_Delete'] = 'Удалить';

?>
