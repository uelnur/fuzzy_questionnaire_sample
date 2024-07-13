# Система тестирования с нечеткой логикой (Тестовое задание)

## Установка

Клонируем репозиторий:

```shell
git clone https://github.com/uelnur/fuzzy_questionnaire_sample.git fuzzy_questionnaire_sample

cd fuzzy_questionnaire_sample
```

В системе должен быть установлен `Docker` и `make`.

Собираем докер контейнеры и запускаем:

```shell
make up-prod
```

Заходим в php контейнер

```shell
make sh
```

Запускаем миграции БД

```shell
php bin/console doctrine:migrations:migrate -n
```

Загружаем тестовые данные

```shell
php bin/console doctrine:fixtures:load -n
```

Проект будет доступен по адресу: `http://localhost:11001/`

## Условия задания

Нужно сделать простую систему тестирования, поддерживающую вопросы с нечеткой логикой и возможностью выбора нескольких вариантов ответа. 

Что такое вопросы с нечеткой логикой?

“2 + 2 = ”

1. 4
2. 3 + 1
3. 10

Правильными ответами тут будут `1 ИЛИ 2` ИЛИ `(1 И 2)`. При этом любые другие комбинации (например, `1 И 3`) не будут считаться верными, несмотря на то, что содержат правильный ответ.

## Что ожидается в качестве результата?

- Cсылка на GitHub / Bitbucket с кодом и инструкцией по разворачиванию проекта
- Проект должен быть обернут в docker
- Пользователь должен иметь возможность пройти тест от начала до конца и в конце увидеть два списка - вопросы на которые он ответил верно и вопросы, где ответы содержали ошибки.
- Должна быть возможность пройти тест сколько угодно раз
- Каждый результат тестирования должен сохраняться в БД (выводить результаты не обязательно)
- (Не обязательно) И вопросы, и ответы для каждого вопроса должны показываться пользователю в случайном порядке при каждой новой серии тестирования

## Условия

- Задание нужно выполнять с использованием Symfony и PostgreSQL
- Внешний вид не важен, авторизация не нужна, админка не нужна, достаточно разово добавить вопросы с ответами в БД
- Дедлайн 1 неделя, но чем раньше вы пришлете задание, тем лучше
