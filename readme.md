#Инструкция

###Необходимый минимум:
1. git
2. composer
3. php + xdebug (для phpunit)
4. apache server

###Запуск проекта
1. git clone в папку домена (openserver или аналог)
2. $ composer i
3. Создать БД с одной таблицей
   1. <code>*create table shorts
      (
      id        int auto_increment primary key,
      full_url  text                 not null,
      short_url varchar(20)          not null,
      is_custom tinyint(1) default 0 not null,
      constraint short_url
      unique (short_url)
      );*</code>
4. Изменить данные подключения к БД тут /core/config.php
   
Готово!

###Инструкция программиста
1. Тесты запускаются командой $ vendor/bin/phpunit
2. HTML Отчет покрытия кода формируется в папке /logs/unit_tests_results
3. Логи записываются в /logs/logs.log
4. В файле /core/config.php находится конфигурация сервиса, оторая задает:
   1. Данные подключения к бд
   2. Путь к файлу логирования
   3. Максимальное время ожидания ответа от url, для которого будет создаваться short url (валидация)
   4. Максимальная длина custom url
   5. Максимальная длина генерируемого short url
   6. Набор символов, из которых можно генерировать short url (этот список нельзя сокращать во избежании возможных коллизий в будующем)

###Инструкция пользователя
1. По адресу 'https://\*domain\*/' находится главная страница, которая позволяет получать сокращенные url
2. Если не вводить custom short url то короткий url сгенерируется, иначе будет выдан custom url, если такогого не существует
3. Если попытаться получить сокращенный url на один и тот же полный url о генерация нового происходить не будет (не относится к custom url)