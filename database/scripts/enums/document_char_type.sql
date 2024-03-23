DROP TYPE IF EXISTS public."document_char_type";

CREATE TYPE public."document_char_type" AS ENUM (
    'NUMERIC',
    'ALPHA_NUMERIC'
);

COMMENT ON TYPE public."document_char_type"
IS 'Define los tipos de carácteres para los tipos de documentos';
