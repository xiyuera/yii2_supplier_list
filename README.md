# Simple project test.

## install

0. Requirements
   
```json
{
    "require": {
        "php": ">=5.6.0",
        "yiisoft/yii2": "~2.0.14",
        "yiisoft/yii2-bootstrap4": "~2.0.0",
        "yiisoft/yii2-swiftmailer": "~2.0.0 || ~2.1.0"
    },
}
```

1. Install from shell

```shell
composer install
./yii serve
# check localhost:8080
```

2. Some detail

* I generate 100000 suppliers example for testing using faker.
* File export using stream write/read. Memory usage is very low.
* Basicly use grid view, no extension or custom widgets added.
* http://104.237.152.114/ 
↑ Can be access from here.