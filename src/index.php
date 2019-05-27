<?php namespace Play;

use Siler\Route;
use Siler\GraphQL;

use Play\Gql\SilerInit;

class Init {
    public static function init($graphqlBuilder) {
        return GraphQL\init(
            GraphQL\schema(...$graphqlBuilder)
        );
    }

    public static function silerGQL() {
        return self::init([ SilerInit::schemaTypes(), SilerInit::resolvers() ]);
    }
}

Route\get('/', Init::silerGQL());