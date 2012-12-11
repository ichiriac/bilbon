# Bilbon : The greatest minimalistic & fantastic ORM
```Made by a geek for geeks```

*What* or *Who* is Bilbon ? Well, in the first place it's an hobbit ...

![Bilbo the hobbit](http://www.passion-cinema.com/img/news/news-2012/bilbo-le-hobbit-peter-jackson-2758.jpg)

... but it's also a lightest weight ORM in da world - less than 100 lines of code.

# U may ask why ?

Well coz it's funny to code light things, coz is 100% tested, coz it's bug free - but overall coz if 
someday you want to CRUD something, this maybe could be helpfull :)

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

As you see every object is an hobbit, but if you want to ... you could also write :

```<?php
use \bilbon\Hobbit as ActiveRecord;
class User extends ActiveRecord {
    public static $table = 'user';
    public function setName( $name ) {
        $this->name = $name;
    }
}
```

... do you want to ? You shouldn't, keep the ORM style and directly use the Hobbit mojo (coz it's cool)

## Using a model

```<?php
$user = new User();
$user->setName('Thorin');
$user->insert();
```

but it's not necessary to define getters & setters, you can dirrectly do :

```<?php
$user = new User();
$user->name = 'Thorin';
$user->insert();
```

## Requesting

```<?php
$users = User::request('select * from users');
print_r($users);
```
## The great Gandalf storage

Gandalf provides the PDO storage. To define a storage engine you can directly define it's configuration :

```<?php
\bilbon\Gandalf::$instances['default'] = array(
    'dsn' => 'mysql:host=localhost,dbname=epic',
    'user' => 'root',
    'password' => ''
);
```

An ActiveRecord could use another storage provider if you configure it :

```<?php
use \bilbon\Gandalf as Storage;
use \bilbon\Hobbit as ActiveRecord;
Storage::$instances['sauron'] = array(
    'dsn' => 'mysql:host=127.0.0.1,dbname=private',
    'user' => 'root',
    'password' => ''
);
class BadGuys extends ActiveRecord {
    public static $storage = 'sauron';
    public static $table = 'bad_guys';
}
```

You can also freely use the PDO connector like this :

```<?php
use \bilbon\Gandalf as Storage;
Storage::$instances['sauron'] = array(
    'dsn' => 'mysql:host=127.0.0.1,dbname=private',
    'user' => 'root',
    'password' => ''
);
Storage::get('sauron')->exec('whatever you want');
```

--------------------

# Disclamer

It's cool if you want to use it over a small business logic domain
as for example 3 or 4 tables, but not more.

Keep in mind that just a CRUD wrapper written for fun ...

It's under MIT license - do what you want with ...

--------------------

# Even more epic

 - https://github.com/github/gollum
 - http://www.youtube.com/watch?v=CN7MJQFz9t4
