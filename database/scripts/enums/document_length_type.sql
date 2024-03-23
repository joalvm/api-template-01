DROP TYPE IF EXISTS public."document_length_type";

CREATE TYPE public."document_length_type" AS ENUM (
    'EXACT',
    'MAX',
    'MIN'
);

COMMENT ON TYPE public."document_length_type"
IS 'Define los tipos de longitud para los tipos de documentos';
