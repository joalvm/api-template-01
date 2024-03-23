DROP TYPE IF EXISTS public."gender";

CREATE TYPE public."gender" AS ENUM (
    'FEMALE',
    'MALE'
);

COMMENT ON TYPE public."gender"
IS 'Define los generos de las personas';
