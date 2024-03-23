DROP FUNCTION IF EXISTS public."fn_user_authenticate"(int4);

CREATE FUNCTION "public"."fn_user_authenticate"(p_session_id int4)
RETURNS jsonb
AS
$FUNCTION$
BEGIN

    RETURN (
        SELECT
            jsonb_build_object(
                'session', to_jsonb(us),
                'user', to_jsonb(u) || jsonb_build_object(
                    'person', to_jsonb(p) || jsonb_build_object(
                        'document_type', to_jsonb(dt)
                    )
                )
            )
        FROM public."user_sessions" AS us
        INNER JOIN public."users" AS u ON u."id" = us."user_id"
        INNER JOIN public."persons" as p ON p."id" = u."person_id"
        INNER JOIN public."document_types" as dt ON dt."id" = p."document_type_id"
        WHERE us."id" = p_session_id
        AND us."closed_at" IS NULL
        AND u."deleted_at" IS NULL
        AND p."deleted_at" IS NULL
    );

END;
$FUNCTION$
LANGUAGE plpgsql;

COMMENT ON FUNCTION public."fn_user_authenticate"
IS 'Función que valida y obtiene la información de una sessión de usuario.';
