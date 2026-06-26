<?php

require_once(__DIR__.'/vendor/autoload.php');

// quest
// Get user list with roles as xml output
// - UserService (as dbservice as dataservice)
// - dbservice - dbconnection, ext crud (all, single,...)
// - dataservice - CRUD, datasource
// - ApikorModel - DbModel, basic data
// - service - functions, singleton
// - model - data, processing functions (update?, calc something in model)
// - mapper - singleton? maps database row to model
// - container - services, mappers?
// - dbconnection
// - URL parser
// - module, controller, action

// Goals 
// ==========================
// error handling!!
// diagnostics - settings, run diagnostics
// log to DB, log to local file
// module, controller, action -> output
// user, roles, permissions
// installation
// model, mapper, service (data service,...)
// DB connection + wrapper
// URL parser
// Engine - diagnostics
// Engine - Configuration (app settings)
// output
// output formats
// FOR auth user:
// -----------------
// get user list
// get user list with roles
// get roles with user nicks
// system stats


// login, logout
// permission
// GET, POST
// search + filter
// handle own codes (403, 404, 500,...)

require_once(__DIR__.'/samples/ubcrm/index.php');