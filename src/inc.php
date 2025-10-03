<?php

namespace Apikor;

// Crucial parts
require_once(__DIR__.'/exc.php');

// Configurations
require_once(__DIR__.'/config.php');

// Core
require_once(__DIR__.'/diagnose.php');
// - commons (inc)
require_once(__DIR__.'/core/commons.php');
// - helpers
require_once(__DIR__.'/core/helpers/msgcreator.php');
// - api (cls)
require_once(__DIR__.'/core/cls/params.php');
require_once(__DIR__.'/core/cls/entity.php');
// - api entities
require_once(__DIR__.'/core/cls/model.php');
require_once(__DIR__.'/core/cls/mapper.php');
require_once(__DIR__.'/core/cls/service.php');
require_once(__DIR__.'/core/cls/controller.php');
// - response
require_once(__DIR__.'/core/response/message.php');
require_once(__DIR__.'/core/response/response.php');

// Db
require_once(__DIR__.'/core/db/db_mysql.php');

// Data provision
require_once(__DIR__.'/container.php');
require_once(__DIR__.'/dataprovider.php');
// - providers
require_once(__DIR__.'/providers/base/data.php');
require_once(__DIR__.'/providers/db.php');
require_once(__DIR__.'/providers/service.php');
require_once(__DIR__.'/providers/controller.php');
require_once(__DIR__.'/providers/mapper.php');
require_once(__DIR__.'/providers/model.php');

// Output
require_once(__DIR__.'/output/iformat.php');
require_once(__DIR__.'/output/output.php');

// Tools
require_once(__DIR__.'/tools/tools.php');


function INC_Entities() {

    $e = []; // basic entities
    // services
    require_once(__DIR__.'/services/base/data.php');
    require_once(__DIR__.'/services/base/db.php');
    $e[] = Entity::CreateService('account', '\Apikor', Entity::TableName('users'));
    // controllers
    require_once(__DIR__.'/models/base/dbmodel.php');
    $e[] = Entity::CreateController('system', 'test', '\Apikor');
    $e[] = Entity::CreateController('system', 'state', '\Apikor');
    $e[] = Entity::CreateController('stats', 'entity', '\Apikor');

    return $e;
}
