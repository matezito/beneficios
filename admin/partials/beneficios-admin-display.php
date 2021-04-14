<div class="wrap">
<h1><?php echo __('Opciones','beneficios')?></h1>
    <form method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label><?php echo __('Página principal','beneficios')?></label></th>
                    <td><?php echo beneficios_options()->beneficios_loop_page_input()?></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <button type="submit" class="button button-primary">Guardar</button>
        </p>
    </form>
    <h1><?php echo __('Emails','beneficios')?></h1>
    <form method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php echo __('Beneficio automatico','beneficios')?></th>
                    <td>
                        <input type="text" name="subject_automatico" class="large-text" placeholder="Asunto" value="<?php echo get_option('subject_automatico')?>" /><br />
                        <p><textarea name="mail_automatico" class="large-text" placeholder="Cuerpo"><?php echo get_option('mail_automatico')?></textarea></p>
                        <p>
                            Merge Tags:<br />
                            Nombre: {{first_name}}<br>
                            Apellido: {{last_name}}<br />
                            Nombre beneficio: {{beneficio_name}}<br />
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Beneficio sorteo','beneficios')?></th>
                    <td>
                        <input type="text" name="subject_sorteo" class="large-text" placeholder="Asunto" value="<?php echo get_option('subject_sorteo')?>" /><br />
                        <p><textarea name="mail_sorteo" class="large-text" placeholder="Cuerpo"><?php echo get_option('mail_sorteo')?></textarea></p>
                        <p>
                            Merge Tags:<br />
                            Nombre: {{first_name}}<br>
                            Apellido: {{last_name}}<br />
                            Nombre beneficio: {{beneficio_name}}<br />
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <button type="submit" class="button button-primary">Guardar</button>
        </p>
    </form>
</div>