<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title> Cambio de contraseña </title>
    </head>
    <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                        <tr>
                            <td style="padding: 30px; text-align: center; background-color: #007bff; color: #ffffff;">
                                <h1 style="margin: 0; font-size: 24px;">Hola, {{ $name }}!</h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 30px; text-align: left; color: #333333;">
                                <p style="font-size: 16px; margin: 0 0 20px;">Recibimos una solicitud para restablecer tu contraseña. Haz clic en el botón de abajo para establecer una nueva.</p>
                                <a href="{{ $frontend_url }}/password/reset?token={{ $token }}" 
                                    style="display: inline-block; padding: 12px 24px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                        Ir al sitio
                                </a>
                                <p style="font-size: 16px; margin: 20px 0;">Si no solicitaste esto, puedes ignorar este correo electrónico.</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px; text-align: center; font-size: 12px; color: #999999;">
                                © {{ $current_year}} King Dreams. Todos los derechos reservados.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
