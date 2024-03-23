DROP TYPE IF EXISTS public."user_role";

CREATE TYPE public."user_role" AS ENUM (
    'ADMIN',
    'USER'
);

COMMENT ON TYPE public."user_role" IS 'Roles de usuarios.';
