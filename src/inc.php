<?php

namespace Apikor;

// Crucial parts
require_once(__DIR__.'/exc.php');

// Configurations
require_once(__DIR__.'/config.php');

// Data provision
require_once(__DIR__.'/container.php');
require_once(__DIR__.'/dataprovider.php');
// - providers
require_once(__DIR__.'/providers/base/data.php');
require_once(__DIR__.'/providers/db.php');

// Core
require_once(__DIR__.'/diagnose.php');
// - commons (inc)
require_once(__DIR__.'/core/cls/commons.php');
// - api (cls)
require_once(__DIR__.'/core/cls/params.php');
require_once(__DIR__.'/core/cls/model.php');
require_once(__DIR__.'/core/cls/mapper.php');
require_once(__DIR__.'/core/cls/service.php');
require_once(__DIR__.'/core/cls/controller.php');
// - models
require_once(__DIR__.'/core/cls/models/base/dbmodel.php');
// -- custom
require_once(__DIR__.'/core/cls/models/account.php');
// - service
require_once(__DIR__.'/core/cls/services/base/data.php');
require_once(__DIR__.'/core/cls/services/base/db.php');
// -- custom
require_once(__DIR__.'/core/cls/services/account.php');
// - helpers
require_once(__DIR__.'/core/cls/helpers/msgcreator.php');
// - response
require_once(__DIR__.'/core/response/message.php');
require_once(__DIR__.'/core/response/response.php');

// Db
require_once(__DIR__.'/core/db/db_mysql.php');

// Output
require_once(__DIR__.'/output/iformat.php');
require_once(__DIR__.'/output/output.php');

// Tools
require_once(__DIR__.'/tools/tools.php');
