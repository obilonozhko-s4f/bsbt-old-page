<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//------------------------ GLOBAL> ADMIN ----------------------------------
$lang['admin.title'] = 'Панель Администрирования';
$lang['admin.edit'] = 'Изменить';
$lang['admin.delete'] = 'Удалить';
$lang['admin.reserv'] = 'Резервация';
$lang['admin.delete_image'] = 'Удалить';
$lang['admin.enlarge_image'] = 'Увеличить';
$lang['admin.delete_all'] = 'Удалить все';
$lang['admin.save_order'] = 'Сохранить порядок';
$lang['admin.values'] = 'Значения';
$lang['admin.answers'] = 'Ответы';
$lang['admin.menu_title'] = 'Меню';
$lang['admin.total'] = 'Всего';
$lang['admin.add'] = '+ Добавить';
$lang['admin.per_page'] = 'На странице';
$lang['admin.per_page_array'] = array('25' => '25', '50' => '50', '100' => '100', 'all' => 'Все');
$lang['admin.clear_sort'] = 'Сортировка по умолчанию';
$lang['admin.search.clear_search_sort_filters'] = 'Сбросить фильтры, поиск и сортировки';
$lang['admin.logout'] = 'Выход';
$lang['admin.logged_out_message'] = 'Вы успешно разлогинились';
$lang['admin.add_edit_back'] = '&lt; Назад';
$lang['admin.add_edit_prev'] = "&lt; пред";
$lang['admin.add_edit_next'] = "след &gt";
$lang['admin.add_child'] = 'Добавить вложенную';
$lang['admin.no_children'] = 'Нет элементов.';
$lang['admin.required_description'] = 'обязательное поле';
$lang['admin.editor'] = 'редактор';
$lang['admin.html'] = 'HTML';
$lang['admin.delete_video'] = 'Удалить видео';
$lang['admin.no_items'] = 'Нет элементов.';
$lang['admin.menu.title'] = 'Меню';
$lang['admin.goto_website'] = 'Перейти на сайт';
$lang['admin.footer_copyright'] = 'BS Travelling 2012';
$lang['admin.footer_support'] = 'Техническая поддрержка';
$lang['admin.yes'] = 'Да';
$lang['admin.no'] = 'Нет';
$lang['admin.filter.all'] = '-- Все --';
$lang['admin.need_to_login_message'] = 'Для перехода в этот раздел необходимо сначала авторизироваться';
$lang['admin.next.error.no_more'] = 'Следующий элемент отсутствует';
$lang['admin.actions_title'] = 'Действия';
$lang['admin.elements_title'] = 'Элементов';
$lang['admin.filter_title'] = 'Фильтры';
$lang['admin.show_all'] = 'Показать все';
$lang['admin.hide_all'] = 'Спрятать все';

$lang['admin.pager.first'] = 'Первая';
$lang['admin.pager.prev'] = 'Предыдущая';
$lang['admin.pager.next'] = 'Следующая';
$lang['admin.pager.last'] = 'Последняя';

$lang['admin.export.select_all_required'] = 'Выбрать все обязательные';
$lang['admin.export.file_preview'] = 'Препросмотр файла';

$lang['admin.export.filters_processed'] = 'Фильтры экспорта';


$lang['image.upload.messages.file_required'] = 'Необходимо выбрать файл .CSV';
$lang['admin.export.fields'] = 'Поля для экспорта';
$lang['admin.export.fields.description'] = 'Только выбранные поля будут присутствовать в файле эксопрта.';
$lang['admin.export.export'] = 'Экспорт';
$lang['admin.export.export.description'] = 'Результатом экспорта является файл .CSV';
$lang['admin.export'] = 'Экспорт';
$lang['admin.export.select_all'] = 'Выбрать все';
$lang['admin.export.deselect_all'] = 'Отменить все';
$lang['admin.export.select_all_import'] = 'Выбрать только разрешённые для импорта';
$lang['admin.change_root_order'] = 'Изменение порядка категорий верхнего уровня';
$lang['admin.import'] = 'Импорт';

$lang['admin.messages.batch_update'] = 'Изменения успешно применены. Затронуто элементов: {count}';
$lang['admin.update'] = 'Применить';
$lang['admin.confirm.entities_delete_batch'] = 'Вы уверены, что хотите удалить выбранные элементы?';
$lang['admin.messages.many_delete'] = 'Удаление выполнено успешно';
//------------------------ IMPORT > START -----------------------------------

$lang['admin.import.type'] = 'Режим Импорта';
$lang['admin.import.type.description'] = '';
$lang['admin.import.type.label'] = 'Режим: ';
$lang['admin.import.type.add_edit'] = 'Добавление и Редактирование';
$lang['admin.import.type.add_only'] = 'Только Добавление';
$lang['admin.import.type.edit_only'] = 'Только Редактирование';

$lang['admin.import.error.first_column_must_be_id'] = 'Первый столбец должен быть &quot;id&quot;';
$lang['admin.import.error.second_column_must_be_parent_id'] = 'Второй столбец должен быть &quot;Родительская категория&quot;';
$lang['image.upload.messages.file_required'] = 'Необходимо выбрать файл .CSV';
$lang['admin.import.error.on_line'] = 'Ошибка при обработке строки {line}';
$lang['admin.import.file_preview'] = 'Вид файла .CSV';
$lang['admin.import.fields'] = 'Поля для импорта';
$lang['admin.import.fields.filters'] = 'Внимание! На импорт будут наложены следующие фильтры';
$lang['admin.import.fields.filters.description'] = 'ВСЕ импортированные элементы будут иметь вышеуказанные значения.';
$lang['admin.import.file_preview.description'] = 'Поле &quot;id&quot; должно быть первым. Порядок остальных полей не имеет значения.';
$lang['admin.import.fields.description'] = 'Только выбранные поля будут обработаны в файле импорта, остальные будут проигнорированы.';
$lang['admin.import.depend.fields'] = 'Зависимые поля';
$lang['admin.import.depend.description'] = 'Значение этих полей заполнется автоматический если они пустые.';
$lang['admin.import.error.missing_required_fields'] = 'В файле импорта отсутствует обязательный столбец &quot;{fname}&quot;';
$lang['admin.import.totally_depend.fields'] = 'Автоматические поля';
$lang['admin.import.totally_depend.description'] = 'Значение этих полей формируется полностью автоматически и не может быть изменено.';

$lang['admin.import.relation.fields'] = 'Поля через запятую';
$lang['admin.import.relation.description'] = 'Значения следующих полей должны быть заданы через запятую (,) или без запятых, если значение одно.<br/>
																							<b>Если вы укажете значение, а такого элемента не существует - он будет создан автоматически.</b>';


$lang['admin.import.image.fields'] = 'Режим иморта изображений! Файл должен быть .ZIP';
$lang['admin.import.image.description'] = 'Для импорта изображений необходимо загрузить архив (.zip) в котором должен находиться 1 файл .csv и файлы с изображениями.</br>
																					 <b>В архиве не должно быть папок. В .csv файле, в колонке "Изображение" должно быть указано только название файла с картинкой, например "image.jpg".</b>';


$lang['admin.import.settings'] = 'Настройки Импорта';
$lang['admin.import.settings.description'] = '';
$lang['admin.import.ignore_errors'] = 'Игнорировать сообщения об ошибках';
$lang['admin.import.error.falied_to_refresh'] = 'Невозможно отредактировать несуществующий товар. Для добавления, поле id должно быть пустым.';

$lang['admin.import.select_all'] = 'Выбрать все';
$lang['admin.import.deselect_all'] = 'Отменить все';
$lang['admin.import.import'] = 'Импорт';
$lang['admin.import.import.description'] = '';
$lang['admin.import.message.imported'] = 'Импорт произведён успешно. Добавлено: {added}. Изменено: {edited}';
//------------------------ IMPORT > END -----------------------------------

//------------------------ WINDOW > START -----------------------------------
$lang['admin.window.title'] = 'Image Manager';
$lang['admin.window.upload_label'] = 'Upload photo';
$lang['admin.window.create_folder'] = 'Create folder';
$lang['admin.window.image_label'] = 'Image Label';
$lang['admin.window.image_name'] = 'Image Name';
$lang['admin.window.image_size'] = 'Size';
$lang['admin.window.image_width'] = 'Width';
$lang['admin.window.image_height'] = 'Height';
$lang['admin.window.image_created_date'] = 'Created';
$lang['admin.window.image_resize'] = 'Image Resize';
$lang['admin.window.image_resize_width'] = 'Resize Width';
$lang['admin.window.image_resize_height'] = 'Resize Height';
$lang['admin.window.image_delete'] = 'Image Delete';
$lang['admin.window.up'] = 'Up';
$lang['admin.window.button_ok'] = 'OK';
$lang['admin.window.folder_name'] = 'Folder Name';
$lang['admin.window.button_create'] = 'Create';
//------------------------ WINDOW > END -----------------------------------


// Действие кнопки
$lang['admin.save_and_add_new'] = 'Сохранить и добавить новый элемент ';
$lang['admin.save_return_to_list'] = 'Сохранить и вернуться к списку';
$lang['admin.save_and_next'] = "Сохранить и перейти к следующему элементу";
$lang['admin.save_permissions'] = 'Сохранить разрешения ';
$lang['admin.save'] = 'Сохранить';
$lang['admin.save_settings'] = 'Сохранить все настройки веб-сайта';
$lang['admin.cancel'] = 'Отмена';
$lang['admin.entity'] = 'Сущность';
$lang['admin.view'] = 'Просмотр';

// Войти
$lang['admin.messages.login_wrong_email_password'] = 'Неправильный Логин или Пароль.';

// Изменение INFO
$lang['admin.change_info'] = 'Изменить информацию';
$lang['admin.change_info.form_title'] = 'Изменить информацию';
$lang['admin.change_info.name'] = 'Название';
$lang['admin.change_info.email'] = 'Электронная почта';
$lang['admin.change_info.new_password'] = 'Новый пароль';
$lang['admin.change_info.confirm_password'] = 'Введите пароль';
$lang['admin.change_info.old_password'] = 'Старый пароль';
$lang['admin.messages.please_change_password'] = 'Пожалуйста, измените ваш пароль.';
$lang['admin.messages.info_successfully_changed'] = 'Информация успешно изменен.';
$lang['admin.skip_step'] = 'Пропустить шаг ';

// Multipleselect
$lang['admin.multipleselect.please_select'] = 'Пожалуйста, выберите по крайней мере одного элемента';
$lang['admin.multipleselect.move_left'] = 'Удалить';
$lang['admin.multipleselect.move_right'] = 'Добавить';
$lang['admin.multipleselect.select_all'] = 'Выбрать все';
$lang['admin.multipleselect.deselect_all'] = 'Отменить все';

// Подтверждение
$lang['admin.confirm.information_dialog_title'] = 'Информация';
$lang['admin.confirm.dialog_title'] = 'Подтверждение';
$lang['admin.confirm.yes_button'] = 'Да';
$lang['admin.confirm.no_button'] = 'Нет';
$lang['admin.confirm.entity_delete'] = 'Вы уверены, что хотите удалить этот элемент?';
$lang['admin.confirm.entities_delete'] = 'Вы уверены, что хотите удалить все выбранные элементы?';
$lang['admin.confirm.no_items_selected'] = 'Нет элементов, выбранных';

// Удаление подтверждения
$lang['admin.add_edit.image_confirm_delete'] = 'Вы уверены, что хотите удалить это изображение?';
$lang['admin.add_edit.video_confirm_delete'] = 'Вы уверены, что хотите удалить это видео?';
$lang['admin.add_edit.file_confirm_delete'] = 'Вы уверены, что хотите удалить этот файл?';

// Забыли пароль?
$lang['admin.image_not_found'] = 'Изображение не найдено';
$lang['admin.messages.image_delete'] = 'Изображение успешно удалено.';

// E-mail Рассылка
$lang['admin.preview_broadcast'] = 'Отправка';
$lang['admin.view_results'] = 'Результаты';
$lang['admin.broadcast.view_results.not_sent'] = 'Результаты будут доступны после отправки.';
$lang['admin.broadcast.preview.title'] = 'Проверка и отправка';
$lang['admin.broadcast.preview.preview'] = 'Вид письма';
$lang['admin.broadcast.preview.recipents'] = 'Получатели';
$lang['admin.send'] = 'Отправить';
$lang['admin.email_broadcast.no_recipients'] = 'Нет получателей';
$lang['admin.email_broadcast.broadcast_email_sent'] = 'Рассылка успешно отправлена {count} получателям.';
$lang['admin.email_broadcast.cannot_edit_a_sent_broadcast'] = 'Невозможно отредактировать рассылку после отправки.';


$lang['admin.menu.broadcast.name'] = 'Email рассылки';
$lang['admin.entity_list.broadcast.list_title'] = 'Email рассылки';
$lang['admin.search.broadcast.description'] = 'по заголовку';
$lang['admin.entity_list.broadcast.filter.is_sent_title'] = 'Отправлена';
$lang['admin.entity_list.broadcast.filter.sent_date_title'] = 'Дата отправки';

$lang['admin.entity_list.broadcast.subject'] = 'Заголовок';
$lang['admin.entity_list.broadcast.recipents_count'] = 'Получателей';
$lang['admin.entity_list.broadcast.is_sent'] = 'Отправлена';
$lang['admin.entity_list.broadcast.sent_date'] = 'Дата отправки';
$lang['admin.entity_list.broadcast.read_count'] = 'Просмотров';
$lang['admin.entity_list.broadcast.link_visited_count'] = 'Переходов';

$lang['admin.add_edit.broadcast.form_title'] = 'Добавить редактировать рассылку';
$lang['admin.add_edit.broadcast.id'] = 'id';
$lang['admin.add_edit.broadcast.subject'] = 'Заголовок';
$lang['admin.add_edit.broadcast.subject.description'] = 'тема письма';
$lang['admin.add_edit.broadcast.text'] = 'Тело';
$lang['admin.add_edit.broadcast.text.description'] = '';
$lang['admin.add_edit.broadcast.is_ajax_layout'] = 'Пустой шаблон';
$lang['admin.add_edit.broadcast.is_ajax_layout.description'] = 'не использывать стандартный шаблон';
$lang['admin.add_edit.broadcast.bcc_email'] = 'Скрытая копия (BCC)';
$lang['admin.add_edit.broadcast.bcc_email.description'] = 'на этот адрес будет отправлена скрытая копия каждого письма из этой рассылки';
$lang['admin.add_edit.broadcast.recipents'] = 'Получатели';
$lang['admin.add_edit.broadcast.recipents.description'] = 'Необходимо загрузить CSV файл с 1 колонкой &quot;Email&quot;';

$lang['admin.messages.broadcast.add'] = 'Рассылка успешно добавлена.';
$lang['admin.messages.broadcast.edit'] = 'Рассылка успешно изменена.';
$lang['admin.messages.broadcast.delete'] = 'Рассылка успешно удалена.';
$lang['admin.messages.broadcast.delete_all'] = 'Рассылки удалены.';

$lang['admin.broadcast.view_results.title'] = 'Результаты рассылки';
$lang['admin.broadcast.view_results.recipent'] = 'Получатель';
$lang['admin.broadcast.view_results.is_read'] = 'Прочитал';
$lang['admin.broadcast.view_results.link'] = 'Перешел по ссылке: ';

//------------------------ GLOBAL END> --------------------- ---------------

//------------------------ LOGIN > START --------------------- --------------
$lang['admin.login.form_title'] = 'Вход';
$lang['admin.login.login_field'] = 'Логин';
$lang['admin.login.password'] = 'Пароль';
$lang['admin.login.login_action'] = 'Войти';
$lang['admin.login.forgot_password'] = 'Забыли пароль?';
//------------------------ LOGIN> END --------------------- ----------------

//------------------------ ЗАБЫЛИ ПАРОЛЬ> START -------------------- ---------------
$lang['admin.forgot_password.form_title'] = 'Забыли пароль?';
$lang['admin.forgot_password.email_field'] = 'Электронная почта';
$lang['admin.forgot_password.back_to_login'] = 'Вернуться к Логин';
$lang['admin.forgot_password.send'] = 'Отправить';
$lang['admin.messages.password_successfully_sent'] = 'Новый пароль был отправлен на вашу электронную почту.';
$lang['admin.messages.forgot_password_wrong_msg'] = 'Неправильный email.';
//------------------------ ЗАБЫЛИ ПАРОЛЬ> END -------------------- -----------------

//------------------------ ПОИСК> START --------------------- -------------
$lang['admin.search.search_action'] = 'Поиск';
$lang['admin.search.search_string'] = 'Поиск:';
$lang['admin.search.search_in'] = 'Где:';
$lang['admin.search.search_type'] = 'Тип поиска:';
$lang['admin.search.search_types'] ["starts_with"] = 'начинается с';
$lang['admin.search.search_types'] ["содержит"] = 'содержит';
$lang['admin.search.search_types'] ["ends_with"] = 'и заканчивая';

$lang['admin.filter.from'] = 'От';
$lang['admin.filter.to'] = 'До';
$lang['admin.filter.for'] = 'За';
$lang['admin.filter.today'] = 'сегодня';
$lang['admin.filter.this_week'] = 'эту неделю';
$lang['admin.filter.this_month'] = 'этот месяц';
$lang['admin.filter.cancel'] = 'сбросить';

//------------------------ ПОИСК> END --------------------- ---------------

//------------------------ ADMIN> START --------------------- --------------
$lang['admin.menu.admin.name'] = 'Администраторы';
$lang['admin.entity_list.admin.list_title'] = 'Администраторы';
$lang['admin.entity_list.admin.add'] = '+ добавить администратора';
$lang['admin.entity_list.admin.name'] = 'Название';
$lang['admin.entity_list.admin.email'] = 'Электронная почта';

$lang['admin.add_edit.admin.form_title'] = 'Добавить/Редактировать администратора';
$lang['admin.add_edit.admin.name'] = 'Название';
$lang['admin.add_edit.admin.name.description'] = '';
$lang['admin.add_edit.admin.email'] = 'Электронная почта';
$lang['admin.add_edit.admin.email.description'] = '';
$lang['admin.add_edit.admin.password'] = 'Пароль';
$lang['admin.add_edit.admin.password.description'] = '';
$lang['admin.add_edit.admin.permissions'] = 'Права доступа';
$lang['admin.add_edit.admin.permissions.description'] = 'Пример: entity_action1 | entity_action2 |...';

$lang['admin.messages.admin.add'] = 'Администратор успешно добавлен.';
$lang['admin.messages.admin.edit'] = 'Администратор успешно изменен.';
$lang['admin.messages.admin.delete'] = 'Администратор успешно удален.';
$lang['admin.messages.admin.delete_all'] = 'Администраторы удалены.';
$lang['admin.messages.admin.cannot_delete_current'] = 'Текущий Администратор не может быть удален.';
$lang['admin.permissions.view'] = 'Просмотр';
$lang['admin.permissions.add'] = 'Добавить';
$lang['admin.permissions.edit'] = 'Редактировать';
$lang['admin.permissions.delete'] = 'Удалить';
$lang['admin.add_edit.admin.permission_form_title'] = 'Права доступа';
//------------------------ ADMIN> END --------------------- --------------

//------------------------ ### PROJECT SPECIFIC ### -----------------------------------

//------------------------ NEWSITEM > START -----------------------------------
$lang['admin.menu.newsitem.name'] = 'Новости';
$lang['admin.entity_list.newsitem.list_title'] = 'Новости';

$lang['admin.entity_list.newsitem.title'] = "Название";
$lang['admin.entity_list.newsitem.is_published'] = 'Опубликована';
$lang['admin.entity_list.newsitem.date'] = 'Дата';

$lang['admin.add_edit.newsitem.form_title'] = 'Добавить/редактировать новость';
$lang['admin.add_edit.newsitem.id'] = 'id';
$lang['admin.add_edit.newsitem.title'] = "Название";
$lang['admin.add_edit.newsitem.title.description'] = '';
$lang['admin.add_edit.newsitem.page_url'] = 'URL-адрес страницы';
$lang['admin.add_edit.newsitem.page_url.description'] = '';
$lang['admin.add_edit.newsitem.content'] = "Содержание";
$lang['admin.add_edit.newsitem.content.description'] = '';
$lang['admin.add_edit.newsitem.description'] = 'Описание';
$lang['admin.add_edit.newsitem.description.description'] = '';
$lang['admin.add_edit.newsitem.date'] = 'Дата';
$lang['admin.add_edit.newsitem.date.description'] = '';
$lang['admin.add_edit.newsitem.is_published'] = 'Опубликована';
$lang['admin.add_edit.newsitem.is_published.description'] = '';
$lang['admin.add_edit.newsitem.image'] = 'Картинка';
$lang['admin.add_edit.newsitem.image.description'] = 'Желательно, чтобы ширина и высота картинки были равны. Например: 600px на 600px';
$lang['admin.add_edit.newsitem.header.title'] = 'SEO Заголовок';
$lang['admin.add_edit.newsitem.header.title.description'] = '';
$lang['admin.add_edit.newsitem.header.description'] = 'SEO Описание';
$lang['admin.add_edit.newsitem.header.description.description'] = '';

$lang['admin.messages.newsitem.add'] = 'Новость успешно добавлен.';
$lang['admin.messages.newsitem.edit'] = 'Новости пункта успешно изменены.';
$lang['admin.messages.newsitem.delete'] = 'Новости пункта успешно удалён.';
$lang['admin.messages.newsitem.delete_all'] = "Новостные сообщения удалены.";
//------------------------ NEWSITEM > END -------------------------------------

//------------------------ CITY > START -----------------------------------
$lang['admin.menu.city.name'] = 'Города';
$lang['admin.entity_list.city.list_title'] = 'Города';

$lang['admin.entity_list.city.title'] = "Название города";

$lang['admin.add_edit.city.form_title'] = 'Добавить/изменить Город';
$lang['admin.add_edit.city.id'] = 'id';
$lang['admin.add_edit.city.title'] = "Название города";
$lang['admin.add_edit.city.title.description'] = '';

$lang['admin.messages.city.add'] = 'Город успешно добавлен.';
$lang['admin.messages.city.edit'] = 'Город успешно изменен.';
$lang['admin.messages.city.delete'] = 'Город успешно удалён.';
$lang['admin.messages.city.delete_all'] = 'Города удалены.';
//------------------------ CITY > END -------------------------------------

//------------------------ PAGE > START -----------------------------------
$lang['admin.menu.page.name'] = 'Страницы';
$lang['admin.entity_list.page.list_title'] = 'Страницы';

$lang['admin.entity_list.page.name'] = 'Название';
$lang['admin.entity_list.page.title'] = "Заголовок";
$lang['admin.entity_list.page.page_url'] = "URL-адрес страницы";
$lang['admin.entity_list.page.is_published'] = 'Опубликована';
$lang['admin.entity_list.page.priority'] = 'Приоритет';

$lang['admin.add_edit.page.form_title'] = 'Добавить/редактировать страницу';
$lang['admin.add_edit.page.id'] = 'id';
$lang['admin.add_edit.page.name'] = 'Название';
$lang['admin.add_edit.page.name.description'] = '';
$lang['admin.add_edit.page.title'] = "Заголовок";
$lang['admin.add_edit.page.title.description'] = '';
$lang['admin.add_edit.page.page_url'] = 'URL-адрес страницы';
$lang['admin.add_edit.page.page_url.description'] = '';
$lang['admin.add_edit.page.content'] = "Содержание";
$lang['admin.add_edit.page.content.description'] = '';
$lang['admin.add_edit.page.is_published'] = 'Опубликована';
$lang['admin.add_edit.page.is_published.description'] = '';
$lang['admin.add_edit.page.is_in_nav'] = 'Отображать в навигации';
$lang['admin.add_edit.page.is_in_nav.description'] = '';
$lang['admin.add_edit.page.is_in_foot'] = 'Отображать в футере';
$lang['admin.add_edit.page.is_in_foot.description'] = '';
$lang['admin.add_edit.page.priority'] = 'Приоритет';
$lang['admin.add_edit.page.priority.description'] = '';
$lang['admin.add_edit.page.header.title'] = 'SEO Заголовок';
$lang['admin.add_edit.page.header.title.description'] = '';
$lang['admin.add_edit.page.header.description'] = 'SEO Описание';
$lang['admin.add_edit.page.header.description.description'] = '';
$lang['admin.add_edit.page.parent_id'] = 'Родительская страница';
$lang['admin.add_edit.page.default_parent_category_value'] = '-- страница не указана --';
$lang['admin.add_edit.page.parent_id.description'] = '';

$lang['admin.messages.page.add'] = 'Страница успешно добавлена.';
$lang['admin.messages.page.edit'] = 'Страница успешно изменена.';
$lang['admin.messages.page.delete'] = 'Страница успешно удалёна.';
$lang['admin.messages.page.delete_all'] = 'Страницы удалены.';
//------------------------ PAGE > END -------------------------------------

//------------------------ OBJECTTYPE > START -----------------------------------
$lang['admin.menu.objecttype.name'] = 'Виды собственности';
$lang['admin.entity_list.objecttype.list_title'] = 'Виды собственности';

$lang['admin.entity_list.objecttype.name'] = 'Название';

$lang['admin.add_edit.objecttype.form_title'] = "Добавить/редактировать Вид собственности";
$lang['admin.add_edit.objecttype.parent_id'] = "Родительский вид собственности";
$lang['admin.add_edit.objecttype.default_parent_category_value'] = "-- не выбран родительский вид собственности --";
$lang['admin.add_edit.objecttype.parent_id.description'] = "";
$lang['admin.add_edit.objecttype.children'] = "Дочерние виды собственности (Типы жилья)";
$lang['admin.add_edit.objecttype.id'] = 'id';
$lang['admin.add_edit.objecttype.name'] = 'Название';
$lang['admin.add_edit.objecttype.name.description'] = '';

$lang['admin.messages.objecttype.add'] = 'Вид собственности успешно добавлен.';
$lang['admin.messages.objecttype.edit'] = 'Вид собственности успешно изменен.';
$lang['admin.messages.objecttype.delete'] = 'Вид собственности успешно удалён.';
$lang['admin.messages.objecttype.delete_all'] = 'Виды собственности удалены.';
//------------------------ OBJECTTYPE > END -------------------------------------

//------------------------OBJECTFEATURE > НАЧАТЬ-----------------------------------
$lang['admin.menu.objectfeature.name'] = 'Особенности объекта';
$lang['admin.entity_list.objectfeature.list_title'] = 'Особенности объекта';

$lang['admin.entity_list.objectfeature.name'] = 'Название';
$lang['admin.entity_list.objectfeature.image'] = 'Иконка';

$lang['admin.add_edit.objectfeature.form_title'] = "Добавить/изменить Особенность объекта";
$lang['admin.add_edit.objectfeature.id'] = 'id';
$lang['admin.add_edit.objectfeature.name'] = 'Название';
$lang['admin.add_edit.objectfeature.name.description'] = '';
$lang['admin.add_edit.objectfeature.image'] = 'Иконка';
$lang['admin.add_edit.objectfeature.image.description'] = 'Высота и широта <b>обязательно</b> должна быть равной 18px';

$lang['admin.messages.objectfeature.add'] = 'Особенность объекта успешно добавлена.';
$lang['admin.messages.objectfeature.edit'] = "Особенность объекта успешно изменена.";
$lang['admin.messages.objectfeature.delete'] = 'Особенность объекта успешно удалена.';
$lang['admin.messages.objectfeature.delete_all'] = 'Особенности объекта удалены.';
//------------------------ OBJECTFEATURE > END -------------------------------------

//------------------------APARTMENTRESERV > НАЧАТЬ-----------------------------------
$lang['admin.menu.apartmentreserv.name'] = 'Резервация объектов';
$lang['admin.entity_list.apartmentreserv.list_title'] = 'Резервация объектов';

$lang['admin.entity_list.apartmentreserv.apartment.id'] = 'Код объекта';
$lang['admin.entity_list.apartmentreserv.date_from'] = 'Дата от';
$lang['admin.entity_list.apartmentreserv.date_to'] = 'Дата до';

$lang['admin.add_edit.apartmentreserv.apartment'] = 'Объект';
$lang['admin.add_edit.apartmentreserv.apartment.default'] = 'Выберете код объекта';
$lang['admin.add_edit.apartmentreserv.apartment.description'] = '';

$lang['admin.entity_list.apartmentreserv.filter.apartment.id_title'] = 'Код объекта';
$lang['admin.entity_list.apartmentreserv.filter.date_to_title'] = 'Дата ДО';
$lang['admin.entity_list.apartmentreserv.filter.date_from_title'] = 'Дата ОТ';

$lang['admin.add_edit.apartmentreserv.form_title'] = 'Добавить/изменить Резервацию объекта';
$lang['admin.add_edit.apartmentreserv.id'] = 'id';
$lang['admin.add_edit.apartmentreserv.date_from'] = 'Дата от';
$lang['admin.add_edit.apartmentreserv.date_from.description'] = '';
$lang['admin.add_edit.apartmentreserv.date_to'] = 'Дата до';
$lang['admin.add_edit.apartmentreserv.date_to.description'] = '';
$lang['admin.add_edit.apartmentreserv.apartment_id'] = 'Код объекта';
$lang['admin.add_edit.apartmentreserv.apartment_id.description'] = '';

$lang['admin.messages.apartmentreserv.add'] = 'Резервация объекта успешно добавлена.';
$lang['admin.messages.apartmentreserv.edit'] = 'Резервация объекта успешно изменена.';
$lang['admin.messages.apartmentreserv.delete'] = 'Резервация объекта успешно удалена.';
$lang['admin.messages.apartmentreserv.delete_all'] = 'Резервации объектов удалены.';
//------------------------APARTMENTRESERV > КОНЕЦ-------------------------------------


//------------------------ORDERS > НАЧАТЬ-----------------------------------
$lang['admin.menu.reservation.name'] = 'Заказы';
$lang['admin.entity_list.reservation.list_title'] = 'Заказы';


$lang['admin.entity_list.reservation.apartment.id'] = 'Код объекта';
$lang['admin.entity_list.reservation.date_from'] = 'Дата от';
$lang['admin.entity_list.reservation.date_to'] = 'Дата до';
$lang['admin.entity_list.reservation.persons'] = 'Персон';
$lang['admin.entity_list.reservation.utype'] = 'Вид лица';
$lang['admin.entity_list.reservation.name'] = 'Имя';
$lang['admin.entity_list.reservation.surname'] = 'Фамилия';
$lang['admin.entity_list.reservation.phone'] = 'Телефон';
$lang['admin.entity_list.reservation.email'] = 'E-mail';
$lang['admin.entity_list.reservation.transfer'] = 'Трансфер';

$lang['admin.add_edit.reservation.form_title'] = 'Добавить/изменить Заказ';
$lang['admin.add_edit.reservation.apartment'] = 'Объект';
$lang['admin.add_edit.reservation.apartment.default'] = '-- выберете объект --';
$lang['admin.add_edit.reservation.apartment.description'] = '';
$lang['admin.add_edit.reservation.date_from'] = 'Дата от';
$lang['admin.add_edit.reservation.date_from.description'] = '';
$lang['admin.add_edit.reservation.date_to'] = 'Дата до';
$lang['admin.add_edit.reservation.date_to.description'] = '';
$lang['admin.add_edit.reservation.persons'] = 'Персон';
$lang['admin.add_edit.reservation.persons.description'] = '';
$lang['admin.add_edit.reservation.persons_info'] = 'Информация о персонах';
$lang['admin.add_edit.reservation.persons_info.description'] = 'Имя, Фамилия, № пасспорта каждой из персон (с новой строки)';
$lang['admin.add_edit.reservation.comments'] = 'Коментарий к заказу';
$lang['admin.add_edit.reservation.comments.description'] = '';
$lang['admin.add_edit.reservation.utype'] = 'Вид лица';
$lang['admin.add_edit.reservation.utype.description'] = '';
$lang['admin.add_edit.reservation.name'] = 'Имя контактного лица';
$lang['admin.add_edit.reservation.name.description'] = '';
$lang['admin.add_edit.reservation.surname'] = 'Фамилия контактного лица';
$lang['admin.add_edit.reservation.surname.description'] = '';
$lang['admin.add_edit.reservation.phone'] = 'Телефон контактного лица';
$lang['admin.add_edit.reservation.phone.description'] = 'В международном формате. Например: +38 (044)235-1327';
$lang['admin.add_edit.reservation.email'] = 'E-mail контактного лица';
$lang['admin.add_edit.reservation.email.description'] = '';
$lang['admin.add_edit.reservation.time_h'] = 'Время прибытия (часы)';
$lang['admin.add_edit.reservation.time_h.description'] = '';
$lang['admin.add_edit.reservation.time_m'] = 'Время прибытия (минуты)';
$lang['admin.add_edit.reservation.time_m.description'] = '';
$lang['admin.add_edit.reservation.transfer'] = 'Трансфер';
$lang['admin.add_edit.reservation.transfer.description'] = '';
$lang['admin.add_edit.reservation.org'] = 'Название организации';
$lang['admin.add_edit.reservation.org.description'] = 'Только для ЮР-лица';
$lang['admin.add_edit.reservation.vat'] = 'ИНН или VAT number';
$lang['admin.add_edit.reservation.vat.description'] = 'Только для ЮР-лица';
$lang['admin.add_edit.reservation.fax'] = 'Факс';
$lang['admin.add_edit.reservation.fax.description'] = 'В международном формате. Например: +38 (044)235-1327';
$lang['admin.add_edit.reservation.country'] = 'Страна';
$lang['admin.add_edit.reservation.country.description'] = '';
$lang['admin.add_edit.reservation.city'] = 'Город';
$lang['admin.add_edit.reservation.city.description'] = '';
$lang['admin.add_edit.reservation.zip'] = 'Почтовый индекс';
$lang['admin.add_edit.reservation.zip.description'] = '';
$lang['admin.add_edit.reservation.street'] = 'Улица';
$lang['admin.add_edit.reservation.street.description'] = '';
$lang['admin.add_edit.reservation.home'] = '№ дома';
$lang['admin.add_edit.reservation.home.description'] = '';
$lang['admin.add_edit.reservation.office'] = '№ квартиры / офиса';
$lang['admin.add_edit.reservation.office.description'] = '';

$lang['admin.messages.reservation.add'] = 'Заказ успешно добавлен.';
$lang['admin.messages.reservation.edit'] = 'Заказ успешно изменен.';
$lang['admin.messages.reservation.delete'] = 'Заказ успешно удалён.';
$lang['admin.messages.reservation.delete_all'] = 'Заказы успешно удалёны.';









//------------------------ORDERSS > КОНЕЦ-------------------------------------


//------------------------ APARTMENT > START -----------------------------------
$lang['admin.menu.apartment.name'] = 'Объекты';
$lang['admin.entity_list.apartment.list_title'] = 'Объекты';

$lang['admin.entity_list.apartment.id'] = 'Код объекта';
$lang['admin.entity_list.apartment.city.title'] = 'Город';
$lang['admin.entity_list.apartment.objecttype.name'] = 'Тип жилья';
$lang['admin.entity_list.apartment.nights_min'] = 'Мин. ночей';
$lang['admin.entity_list.apartment.persons_max'] = 'Макс. персон';
$lang['admin.entity_list.apartment.price_out'] = 'Цена';


$lang['admin.search.apartment.description'] = 'введите код объекта';


$lang['admin.entity_list.apartment.filter.objecttype.id_title'] = 'Тип жилья';
$lang['admin.entity_list.apartment.filter.city.id_title'] = 'Город';
$lang['admin.entity_list.apartment.filter.date_from_title'] = 'Дата от';
$lang['admin.entity_list.apartment.filter.date_to_title'] = 'Дата до';


$lang['admin.add_edit.apartment.form_title'] = 'Добавить/изменить Объект';
$lang['admin.add_edit.apartment.id'] = 'id';
$lang['admin.add_edit.apartment.objecttype'] = 'Тип жилья';
$lang['admin.add_edit.apartment.objecttype.description'] = '';
$lang['admin.add_edit.apartment.objecttype.default'] = '-- Выберете Тип жилья --';
$lang['admin.add_edit.apartment.objectfeatures.to'] = 'Особенности объекта';
$lang['admin.add_edit.apartment.objectfeatures.from'] = 'Все Особенности';
$lang['admin.add_edit.apartment.description'] = 'Описание';
$lang['admin.add_edit.apartment.description.description'] = '';
$lang['admin.add_edit.apartment.price_in'] = 'Цена (входящая)';
$lang['admin.add_edit.apartment.price_in.description'] = '';
$lang['admin.add_edit.apartment.price_out'] = 'Цена (исходящая)';
$lang['admin.add_edit.apartment.price_out.description'] = '';
$lang['admin.add_edit.apartment.nights_min'] = 'Минимум ночей';
$lang['admin.add_edit.apartment.nights_min.description'] = '';
$lang['admin.add_edit.apartment.persons_max'] = 'Максимум персон';
$lang['admin.add_edit.apartment.persons_max.description'] = '';
$lang['admin.add_edit.apartment.space'] = 'Площадь объекта';
$lang['admin.add_edit.apartment.space.description'] = '';
$lang['admin.add_edit.apartment.latitude'] = 'Широта';
$lang['admin.add_edit.apartment.latitude.description'] = 'определится автоматически после сохранения';
$lang['admin.add_edit.apartment.longitude'] = 'Долгота';
$lang['admin.add_edit.apartment.longitude.description'] = 'определится автоматически после сохранения';
$lang['admin.add_edit.apartment.post_index'] = 'Почтовый индекс';
$lang['admin.add_edit.apartment.post_index.description'] = '';
$lang['admin.add_edit.apartment.city'] = 'Город';
$lang['admin.add_edit.apartment.city.description'] = '';
$lang['admin.add_edit.apartment.city.default'] = '-- Выберете Город --';
$lang['admin.add_edit.apartment.district'] = 'Район (Округ)';
$lang['admin.add_edit.apartment.district.description'] = '';
$lang['admin.add_edit.apartment.street'] = 'Улица';
$lang['admin.add_edit.apartment.street.description'] = '';
$lang['admin.add_edit.apartment.flat_num'] = 'Номер квартиры';
$lang['admin.add_edit.apartment.flat_num.description'] = '';
$lang['admin.add_edit.apartment.house_num'] = 'Номер дома';
$lang['admin.add_edit.apartment.house_num.description'] = '';
$lang['admin.add_edit.apartment.sale'] = 'Горячее предложение';
$lang['admin.add_edit.apartment.sale.description'] = '';
$lang['admin.add_edit.apartment.is_published'] = 'Опубликован';
$lang['admin.add_edit.apartment.is_published.description'] = '';
$lang['admin.add_edit.apartment.image'] = 'Картинка (главная)';
$lang['admin.add_edit.apartment.image.description'] = 'Желательно, чтобы ширина и высота картинки были равны. Например: 600px на 600px';
$lang['admin.add_edit.apartment.images'] = 'Фотографии объекта для галереи';
$lang['admin.add_edit.apartment.images.description'] = '';
$lang['admin.add_edit.apartment.header.title'] = 'SEO Заголовок';
$lang['admin.add_edit.apartment.header.title.description'] = '';
$lang['admin.add_edit.apartment.header.description'] = 'SEO Описание';
$lang['admin.add_edit.apartment.header.description.description'] = '';

$lang['admin.messages.apartment.add'] = 'Объект успешно добавлен.';
$lang['admin.messages.apartment.edit'] = 'Объект успешно изменён.';
$lang['admin.messages.apartment.delete'] = 'Объект успешно удалён.';
$lang['admin.messages.apartment.delete_all'] = 'Объекты удалены.';
//------------------------ APARTMENT > END -------------------------------------

//------------------------ SETTINGS > START -----------------------------------
$lang['admin.menu.settings.name'] = 'Настройки';
$lang['admin.entity_list.settings.list_title'] = 'Настройки';


$lang['admin.add_edit.settings.form_title'] = 'Добавить/Изменить Настройки';

$lang['admin.settings.groups.home_page'] = 'Главная страница';
$lang['admin.add_edit.settings.home_page_title'] = 'SEO заголовок';
$lang['admin.add_edit.settings.home_page_title.description'] = '';
$lang['admin.add_edit.settings.home_page_description'] = 'SEO описание';
$lang['admin.add_edit.settings.home_page_description.description'] = '';

$lang['admin.messages.settings.add'] = 'Настройка успешно добавлена.';
$lang['admin.messages.settings.edit'] = 'Настройка успешно изменена.';
$lang['admin.messages.settings.delete'] = 'Настройка успешно удалена.';
$lang['admin.messages.settings.delete_all'] = 'Настройки deleted.';
//------------------------ SETTINGS > END -------------------------------------
