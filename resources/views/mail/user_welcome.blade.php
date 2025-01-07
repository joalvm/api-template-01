<div
  style="font-family: Roboto, Tahoma, Verdana, Segoe, Arial, sans-serif;margin: 20px 0;padding: 0;background: #f0f0f0;color: #333;">
  <div
    style="max-width: 600px;margin: 2% auto 0 auto;background: #fff;border-radius: 8px;border: 1px solid #ccc;">
    <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: none;">
      <tr id="header">
        <td style="padding: 0; border: none;">
          <table
            style="border-collapse: collapse; border-spacing: 0; width: 100%; border: none; text-align: center; height: 150px; border-radius: 8px 8px 0 0; background-color:#0d68ec;">
            <tr>
              <td style="padding: 0; border: none;">
                <img src="{{ $message->embed(public_path('logo.png')) }}"
                  alt="Logotipo de {{ Config::get('app.name') }}"
                  style="max-width: 200px; margin: 0 auto;" />
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr id="content">
        <td style="padding: 0; border: none;">
          <table
            style="border-collapse: collapse; border-spacing: 0; width: 100%; border: none; max-width: 75%; margin: 20px auto; font-size: 14px;">
            <tr>
              <td style="padding: 0; border: none;">
                <table
                  style="border-collapse: collapse; border-spacing: 0; width: 100%; border: none; line-height: 1.5; margin: 20px auto;">
                  <tr>
                    <td style="padding: 0; border: none;">
                      <h1 style="margin: 0; font-size: 32px; font-weight: bold; margin-bottom: 20px;">
                        Bienvenido a {{ Config::get('app.name') }}!
                      </h1>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding: 0; border: none;">
                      <p style="margin: 0; font-size: 16px;">
                        Hola <b>{{ $user->person->names }}</b>,<br>
                        ¡Estamos muy contentos de tenerte a bordo!
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="padding: 0; border: none;">
                <hr style="border: 0; border-top: 2px solid #f0f0f0; margin: 0;">
              </td>
            <tr>
              <td style="padding: 0; border: none;">
                <table
                  style="margin: 20px auto; border-collapse: collapse; border-spacing: 0; width: 100%; border: none; font-size: 14px">
                  <tr>
                    <td style="padding: 0; border: none; padding: 5px 0;" colspan="2">
                      <p style="margin: 0; margin-bottom: 25px;font-weight: bold;font-size: 16px;">Aquí tus
                        datos de acceso:
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding: 0; border: none; padding: 5px 0;">
                      <p style="margin: 0; font-weight: bold; color: #656565;" class="label">Email</p>
                    </td>
                    <td style="padding: 0; border: none; padding: 5px 0;">{{ $user->email }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 0; border: none; padding: 5px 0;">
                      <p style="margin: 0; font-weight: bold; color: #656565;" class="label">Contraseña</p>
                    </td>
                    <td style="padding: 0; border: none; padding: 5px 0;">{{ $user->realPassword }}</td>
                  </tr>
                  <tr>
                    <td style="padding: 0; border: none; padding: 5px 0;" colspan="2">
                      <table
                        style="margin: 20px auto; border-collapse: collapse; border-spacing: 0; width: 100%; border: none; margin-bottom: 0;">
                        <tr>
                          <td style="padding: 0; border: none; padding: 5px 0;">
                            <a href="{{ $redirectUrl }}" role="button"
                              style="display: inline-block; height: 36px; padding: 0 24px; font-weight: bold; line-height: 36px; background: #0d68ec; color: #fff; text-decoration: none; border-radius: 5px;">
                              Iniciar sesión ahora</a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="padding: 0; border: none;">
                <hr style="border: 0; border-top: 2px solid #f0f0f0; margin: 0;">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr id="footer">
        <td style="padding: 0; border: none;">
          <table
            style="margin: 20px auto; border-collapse: collapse; border-spacing: 0; width: 100%; border: none; text-align: center;">
            <tr>
              <td style="padding: 0; border: none;">
                <p style="margin: 0; font-size: 12px; color: #999;" class="contact-info">
                  ¿Preguntas? Responde a este correo electrónico o ponte en contacto con nosotros en
                  <br>
                  <a href="mailto:support@ballparkapp.com" class="contact-link"
                    style="font-weight: lighter; color: #007bff; text-decoration: none;">support@ballparkapp.com</a>.
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <table style="border-collapse: collapse; border-spacing: 0; width: 100%; border: none;">
      <tr>
        <td style="padding: 0; border: none;">
          <p style="margin: 0; font-size: 12px; color: #999; margin-top: 20px; text-align: center; margin-bottom: -25px;"
            class="copyright">
            © 2023 {{ Config::get('app.name') }}. Todos los derechos reservados.
          </p>
        </td>
      </tr>
    </table>
  </div>
</div>
