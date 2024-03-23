DROP TRIGGER IF EXISTS "ai_update_last_login_user" on public."user_sessions";
DROP FUNCTION IF EXISTS public."tr_update_last_login_user"();

CREATE FUNCTION public."tr_update_last_login_user"()
RETURNS TRIGGER
AS
$TRIGGER$
BEGIN

    UPDATE public."users"
    SET
        "login_at" = NEW."created_at",
        "updated_at" = CURRENT_TIMESTAMP
    WHERE "id" = NEW."user_id";

    RETURN NEW;
END;
$TRIGGER$
LANGUAGE plpgsql;

COMMENT ON FUNCTION public."tr_update_last_login_user"
IS 'Función que usa el trigger public."user_sessions"."ai_update_last_login_user"';

CREATE TRIGGER "ai_update_last_login_user"
AFTER INSERT ON public."user_sessions"
FOR EACH ROW EXECUTE PROCEDURE public."tr_update_last_login_user"();

COMMENT ON TRIGGER "ai_update_last_login_user" ON public."user_sessions"
IS 'Actualiza la columna "login_at" de la tabla public."users"';
