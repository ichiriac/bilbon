# Bilbon : The greatest minimalistic ORM

What/Who is Bilbon ? Well, in the first place it's an hobbit ...

![Bilbo the hobbit](http://www.passion-cinema.com/img/news/news-2012/bilbo-le-hobbit-peter-jackson-2758.jpg)

... but it's also a lightest weight ORM in da world - less 75 lines of code.

# How does it works ?

## Creating a model

```<?php
class User extends \bilbon\Hobbit {
    public static $table = 'user';
    public function setName( $name ) {
        $this->name = $name;
    }
}
```

## Using a model

```<?php
\hobbit\Gandalf::$instances['default'] = array();
$user = new User();
$user->setName('Thorin');
$user->insert();
```

## Requesting

```<?php
\hobbit\Gandalf::$instances['default'] = array();
$users = User::request('select * from users');
print_r($users);
```

# Disclamer

It's cool if you want to use it over a small business logic domain
as for example 3 or 4 tables, but not more.

Keep in mind that just a CRUD wrapper written for fun ...

# Even more epic

https://github.com/github/gollum
http://www.youtube.com/watch?v=CN7MJQFz9t4
