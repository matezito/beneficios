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
</div>