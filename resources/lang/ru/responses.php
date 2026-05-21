<?php

declare(strict_types=1);

return [
    // Общие
    'common.success' => 'Успешно выполнено',
    'common.error' => 'Произошла ошибка',
    'common.not_found' => 'Запись не найдена',
    'common.forbidden' => 'Доступ запрещён',
    'common.unauthorized' => 'Не авторизован',

    // Agent
    'agent.list' => 'Список AI-агентов успешно получен',
    'agent.item' => 'Данные AI-агента успешно получены',
    'agent.create' => 'AI-агент успешно создан',
    'agent.update' => 'AI-агент успешно обновлён',
    'agent.delete' => 'AI-агент успешно удалён',
    'agent.restore' => 'AI-агент успешно восстановлен',
    'agent.not_found' => 'AI-агент не найден',
    'agent.create_forbidden' => 'У вас нет прав для создания AI-агента',
    'agent.update_forbidden' => 'У вас нет прав для обновления AI-агента',
    'agent.delete_forbidden' => 'У вас нет прав для удаления AI-агента',

    // Case
    'case.list' => 'Список кейсов успешно получен',
    'case.item' => 'Данные кейса успешно получены',
    'case.create' => 'Кейс успешно создан',
    'case.update' => 'Кейс успешно обновлён',
    'case.delete' => 'Кейс успешно удалён',
    'case.restore' => 'Кейс успешно восстановлен',
    'case.not_found' => 'Кейс не найден',
    'case.create_forbidden' => 'У вас нет прав для создания кейса',
    'case.update_forbidden' => 'У вас нет прав для обновления кейса',
    'case.delete_forbidden' => 'У вас нет прав для удаления кейса',

    // Article
    'article.list' => 'Список статей успешно получен',
    'article.item' => 'Данные статьи успешно получены',
    'article.create' => 'Статья успешно создана',
    'article.update' => 'Статья успешно обновлена',
    'article.delete' => 'Статья успешно удалена',
    'article.restore' => 'Статья успешно восстановлена',
    'article.not_found' => 'Статья не найдена',
    'article.slug_exists' => 'Статья с таким URL-адресом уже существует',
    'article.create_forbidden' => 'У вас нет прав для создания статьи',
    'article.update_forbidden' => 'У вас нет прав для обновления статьи',
    'article.delete_forbidden' => 'У вас нет прав для удаления статьи',
    'article.publish_forbidden' => 'Невозможно опубликовать статью без даты публикации',

    // Contact
    'contact.create' => 'Заявка успешно отправлена',
    'contact.list' => 'Список заявок успешно получен',
    'contact.item' => 'Данные заявки успешно получены',
    'contact.update' => 'Заявка успешно обновлена',
    'contact.delete' => 'Заявка успешно удалена',
    'contact.status_updated' => 'Статус заявки успешно обновлён',
    'contact.not_found' => 'Заявка не найдена',
    'contact.create_failed' => 'Не удалось отправить заявку',
    'contact.invalid_phone' => 'Неверный формат номера телефона',
    'contact.name_required' => 'Поле имени обязательно для заполнения',
    'contact.phone_required' => 'Поле телефона обязательно для заполнения',

    // Partner
    'partner.variants' => 'Список вариантов партнёрства успешно получен',
    'partner.steps' => 'Список шагов партнёрства успешно получен',
    'partner.benefits' => 'Список преимуществ партнёрства успешно получен',

    // Partner Variants CRUD
    'partner.variant.list' => 'Список вариантов партнёрства успешно получен',
    'partner.variant.item' => 'Данные варианта партнёрства успешно получены',
    'partner.variant.create' => 'Вариант партнёрства успешно создан',
    'partner.variant.update' => 'Вариант партнёрства успешно обновлён',
    'partner.variant.delete' => 'Вариант партнёрства успешно удалён',
    'partner.variant.not_found' => 'Вариант партнёрства не найден',

    // Partner Steps CRUD
    'partner.step.list' => 'Список шагов партнёрства успешно получен',
    'partner.step.item' => 'Данные шага партнёрства успешно получены',
    'partner.step.create' => 'Шаг партнёрства успешно создан',
    'partner.step.update' => 'Шаг партнёрства успешно обновлён',
    'partner.step.delete' => 'Шаг партнёрства успешно удалён',
    'partner.step.not_found' => 'Шаг партнёрства не найден',

    // Partner Benefits CRUD
    'partner.benefit.list' => 'Список преимуществ партнёрства успешно получен',
    'partner.benefit.item' => 'Данные преимущества партнёрства успешно получены',
    'partner.benefit.create' => 'Преимущество партнёрства успешно создано',
    'partner.benefit.update' => 'Преимущество партнёрства успешно обновлено',
    'partner.benefit.delete' => 'Преимущество партнёрства успешно удалено',
    'partner.benefit.not_found' => 'Преимущество партнёрства не найдено',

    // ProcessStep
    'process_step.list' => 'Список шагов внедрения успешно получен',
    'process_step.item' => 'Данные шага внедрения успешно получены',
    'process_step.create' => 'Шаг внедрения успешно создан',
    'process_step.update' => 'Шаг внедрения успешно обновлён',
    'process_step.delete' => 'Шаг внедрения успешно удалён',
    'process_step.not_found' => 'Шаг внедрения не найден',

    // MarqueeItem
    'marquee.list' => 'Список элементов бегущей строки успешно получен',
    'marquee.item' => 'Данные элемента бегущей строки успешно получены',
    'marquee.create' => 'Элемент бегущей строки успешно создан',
    'marquee.update' => 'Элемент бегущей строки успешно обновлён',
    'marquee.delete' => 'Элемент бегущей строки успешно удалён',
    'marquee.not_found' => 'Элемент бегущей строки не найден',

    // Validation
    'validation.required' => 'Поле :attribute обязательно для заполнения',
    'validation.string' => 'Поле :attribute должно быть строкой',
    'validation.max' => 'Поле :attribute не должно превышать :max символов',
    'validation.min' => 'Поле :attribute должно содержать не менее :min символов',
    'validation.email' => 'Поле :attribute должно быть корректным email-адресом',
    'validation.uuid' => 'Поле :attribute должно быть корректным UUID',
    'validation.exists' => 'Выбранное значение для :attribute не существует',
    'validation.unique' => 'Такое значение поля :attribute уже существует',
    'validation.boolean' => 'Поле :attribute должно быть true или false',
    'validation.array' => 'Поле :attribute должно быть массивом',
    'validation.in' => 'Выбранное значение для :attribute некорректно',
    'validation.numeric' => 'Поле :attribute должно быть числом',
    'validation.integer' => 'Поле :attribute должно быть целым числом',
    'validation.url' => 'Поле :attribute должно быть корректным URL',
    'validation.date' => 'Поле :attribute должно быть корректной датой',
];
