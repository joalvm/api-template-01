CREATE TABLE public."departments"(
    "id" int4 GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY NOT NULL,
    "name" varchar NOT NULL,
    "code" char(2) NOT NULL,
    "latitude" float8 NULL,
    "longitude" float8 NULL,
    "created_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamptz(0) NULL,
    "deleted_at" timestamptz(0) NULL,
    UNIQUE NULLS NOT DISTINCT ("code", "deleted_at")
)
;

CREATE TABLE public."provinces"(
    "id" int4 GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY NOT NULL,
    "department_id" int4 NOT NULL,
    "name" varchar NOT NULL,
    "code" char(4) NOT NULL,
    "latitude" float8 NULL,
    "longitude" float8 NULL,
    "created_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamptz(0) NULL,
    "deleted_at" timestamptz(0) NULL,
    UNIQUE NULLS NOT DISTINCT ("code", "deleted_at")
)
;

CREATE TABLE public."districts"(
    "id" int4 GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY NOT NULL,
    "province_id" int4 NOT NULL,
    "name" varchar NOT NULL,
    "code" char(6) NOT NULL,
    "latitude" float8 NULL,
    "longitude" float8 NULL,
    "created_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamptz(0) NULL,
    "deleted_at" timestamptz(0) NULL,
    UNIQUE NULLS NOT DISTINCT ("code", "deleted_at")
)
;

CREATE TABLE public."document_types" (
    "id" int4 GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY NOT NULL,
    "name" varchar NOT NULL,
    "abbr" varchar NOT NULL,
    "length_type" public."document_length_type" NOT NULL,
    "length" int2 NOT NULL,
    "char_type" public."document_char_type" NOT NULL,
    "created_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamptz(0) NULL,
    "deleted_at" timestamptz(0) NULL,
    UNIQUE NULLS NOT DISTINCT ("name", "deleted_at"),
    UNIQUE NULLS NOT DISTINCT ("abbr", "deleted_at")
)
;

CREATE TABLE public."persons" (
    "id" int4 GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY NOT NULL,
    "names" varchar NOT NULL,
    "last_names" varchar NOT NULL,
    "gender" public."gender" NOT NULL,
    "document_type_id" int4 NOT NULL,
    "id_document" varchar NOT NULL,
    "email" varchar NULL,
    "created_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamptz(0) NULL,
    "deleted_at" timestamptz(0) NULL,
    UNIQUE NULLS NOT DISTINCT ("id_document", "deleted_at")
)
;

CREATE TABLE public."users" (
    "id" int4 GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY NOT NULL,
    "person_id" int4 NOT NULL,
    "role" public."user_role" NOT NULL DEFAULT 'USER',
    "email" varchar NOT NULL,
    "password" varchar NOT NULL,
    "salt" varchar(16) NOT NULL,
    "avatar_url" varchar NULL,
    "verification_token" varchar NULL,
    "verified_at" timestamptz(0) NULL,
    "password_reset_token" varchar NULL,
    "password_reset_at" timestamptz(0) NULL,
    "login_at" timestamptz(0) NULL,
    "enabled" bool NOT NULL DEFAULT TRUE,
    "super_admin" bool NOT NULL DEFAULT FALSE,
    "created_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamptz(0) NULL,
    "deleted_at" timestamptz(0) NULL,
    UNIQUE NULLS NOT DISTINCT ("email", "deleted_at"),
    UNIQUE NULLS NOT DISTINCT ("person_id", "deleted_at")
)
;

CREATE TABLE public."user_sessions" (
    "id" int4 GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY NOT NULL,
    "user_id" int4 NOT NULL,
    "token" varchar NOT NULL,
    "expire_at" timestamptz(0) NOT NULL,
    "ip" varchar NOT NULL,
    "browser" varchar NOT NULL,
    "browser_version" varchar NOT NULL,
    "platform" varchar NOT NULL,
    "platform_version" varchar NOT NULL,
    "closed_at" timestamptz(0) NULL,
    "created_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE ("token")
)
;

ALTER TABLE public."persons" ADD CONSTRAINT "fk_document_types_1" FOREIGN KEY ("document_type_id") REFERENCES public."document_types" ("id");

ALTER TABLE public."users" ADD CONSTRAINT "fk_persons_1" FOREIGN KEY ("person_id") REFERENCES public."persons" ("id");

ALTER TABLE public."user_sessions" ADD CONSTRAINT "fk_users_1" FOREIGN KEY ("user_id") REFERENCES public."users" ("id");

ALTER TABLE public."provinces" ADD CONSTRAINT "fk_departments_1" FOREIGN KEY ("department_id") REFERENCES public."departments" ("id");

ALTER TABLE public."districts" ADD CONSTRAINT "fk_provinces_1" FOREIGN KEY ("province_id") REFERENCES public."provinces" ("id");
