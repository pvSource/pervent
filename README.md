# URL-адреса

/ - главная страница сайта (todo: поджумать позже)

/contest/ - список конкурсов

/contest/{contestCode}/ - детальная страница конкурса

/contest/{contestCode}/{workId}/ - детальная страница работы

# Описание сущностей
## Сущность Contest
Таблица contest

Поля:
    
-id

-code (код уникальный)

-name (название)

-description (описание)

-beginAt (дата-время начала)

-finishAt (дата-время окончания)

## Сущность Work
Таблица work

Поля:

-id

-name (название)

-contest_id (референс - contest)

-description