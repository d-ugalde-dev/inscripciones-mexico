/** AUTH DDL SECTION START */

create table users (
	id BIGINT auto_increment NOT NULL PRIMARY KEY,
	email varchar(320) NOT NULL UNIQUE,
	password varchar(2048) NULL,
	name varchar(120) NOT NULL,
	avatar varchar(2048) NOT NULL,
	user_status_id BIGINT NOT NULL,
	status_comments varchar(120) NULL,
	created_on DATETIME NOT NULL,
	updated_on DATETIME NULL,
	updated_by BIGINT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


create table user_statuses (
	id BIGINT auto_increment NOT NULL PRIMARY KEY,
	name varchar(120) NOT NULL UNIQUE,
	is_blocking tinyint(1)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;


create table authentication_sources (
	id BIGINT auto_increment NOT NULL PRIMARY KEY,
	name varchar(120) NOT NULL UNIQUE
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

create table authentication_source_by_user (
	authentication_source_id BIGINT NOT NULL,
	user_id BIGINT NOT NULL,
	name varchar(120) NOT NULL,
	avatar varchar(2048) NOT NULL,
	last_login_on DATETIME NOT NULL,
	created_on DATETIME NOT NULL,
	updated_on DATETIME NULL,
	updated_by BIGINT NULL,
	PRIMARY KEY(authentication_source_id, user_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

create table roles (
	id BIGINT auto_increment NOT NULL PRIMARY KEY,
	name varchar(120) NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

create table permissions (
	id BIGINT auto_increment NOT NULL PRIMARY KEY,
	name varchar(120) NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

create table permissions_by_role (
	permission_id BIGINT NOT NULL,
	role_id BIGINT NOT NULL,
	PRIMARY KEY(permission_id, role_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

create table permissions_by_user (
	permission_id BIGINT NOT NULL,
	user_id BIGINT NOT NULL,
	PRIMARY KEY(permission_id, user_id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

/** AUTH DDL SECTION END */

/** CATALOG SECTION START */

insert into user_statuses (name, is_blocking) values ('active', 0);
insert into user_statuses (name, is_blocking) values ('suspended', 1);
commit;

insert into authentication_sources (name) values ('inscripciones-mexico');
insert into authentication_sources (name) values ('google');
insert into authentication_sources (name) values ('facebook');
commit;

insert into roles (name) values ('root');
insert into roles (name) values ('help-desk');
insert into roles (name) values ('administrator');
commit;

/** CATALOG SECTION END */

COMMIT;
