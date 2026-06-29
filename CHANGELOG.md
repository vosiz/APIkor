# Change log
## Current version
### 0.4.0 - HTML format
- HtmlFormat — full HTML document output via va-tools HtmlBuilder
- header rendered as ul, payload recursively as ul/headings by type
- Formatter::FORMATS constant as single source of truth for valid format keys
- Config::ValidateFormatDefault uses Formatter::FORMATS
- va-tools dependency bumped to >=1.9.0

## History
### 0.3.0 - Deployment
- Deployer: auto-install on first request when apikor_info not found
- Deployer: Install() runs all migrations ordered by filename date, Update() runs only newer ones (tolerant mode)
- Deployer: creates required directories (logs) on deploy, sets permissions
- Engine: Deploy() public method, CreateDeployer() wired to deploy.base_path config
- DbConnection: Raw() for parameterized raw SQL (used by migrations)
- inc.php: IncludeFiles/IncludeDir wrappers via Io\Inc, models/services auto-loaded with IncludeDir
- logger: Dir::Create() + SetPermissions() instead of raw mkdir
### 0.2.0 - Core DB tables
- apikor_info: installation tracking (version, installed, updated)
- apikor_logs: fatal error logging with guid, origin, message
- apikor_comments: polymorphic comments (table_name + record_id)
- apikor_settings: key-value app configuration with type and description

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

### 0.0.2 - Engine work
- engine pathing and configuration
- test response and formatting
- test CRM project (test project)

### 0.0.1 - (Pre)Basics
- engine pre-basics
- configurator and configs
- simple diagnostics
- readme
- planning
