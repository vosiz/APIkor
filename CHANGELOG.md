# Change log
## Current version
### 0.1.0 - Big refactor
- engine routing: module/controller/action + versioned actions
- response pipeline: Header, Payload types, ResponseFactory (200/400/403/404/500)
- DB layer: DbService with CRUD, reflection mapper, Container with singletons
- model hierarchy: Model → DataModel → DbRawModel → DbModel
- URL parser rewrite: proper prefix stripping, CheckRequired, pattern info
- config system with validation and leak fallback
- users: migration, roles, permissions (wildcard RBAC), UsersService, StatsUsersModel
- output formatters: xml, var, pre
- Io utilities via vosiz/php-utils v1.11.0 (Path, File, Dir, Inc)
- UBCRM concept-proof: stats/users/counts working

## History
### 0.0.2 - Engine work
- engine pathing anc ocnfiguration
- test response and formatting
- test CRM project (test project)
### 0.0.1 - (Pre)Basics
- engine pre-basics
- configurator and configs
- simple diagnostics
- readme
- planning