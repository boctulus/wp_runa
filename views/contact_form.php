<?php

use boctulus\SW\libs\RUT;

js_file('contact_form.js');
// js_file('contact_form.css')

RUT::formatear();

?>

<div class="container">
    <h1>Datos de contacto</h1>

    <table class="form-table" role="presentation">
        <tbody>

            <tr class="billing_company-wrap">
                <th><label for="billing_company">Nombre <span class="description">(obligatorio)</span></label></th>
                <td><input type="text" name="billing_company" id="billing_company" disabled="disabled" class="regular-text"> 
                <!-- <span class="description">Nombre completo o razón social</span> -->
            </td>
            </tr>

            <tr class="rut-wrap">
                <th><label for="rut">RUT</label></th>
                <td>
                    <input type="text" name="rut" id="rut" class="regular-text">
                    <span class="rut-error-message" style="color:red;display:none;"></span>
                </td>                
            </tr>

            <tr class="address-wrap">
                <th><label for="address">Dirección <span class="description">(obligatorio)</span></label></th>
                <td><input type="text" name="address" id="address" class="regular-text"></td>
            </tr>

            <tr class="giro-wrap">
                <!-- <th><label for="giro">Giro</label></th>
                <td><input type="text" name="giro" id="giro" class="regular-text"></td> -->

                <th>
                    <label for="display_name">Giro<span class="description">(obligatorio)</span></label>
                </th>
                <td>
                    <select name="display_giro" id="display_giro">
                        <option selected="selected"></option>
                        <option id="sin giro">Sin giro</option>
                        <option id="con giro">Con giro</option>
                    </select>
                </td>
            </tr>

            <tr class="phone-wrap">
                <th><label for="phone">Teléfono <span class="description">(obligatorio)</span></label></th>
                <td><input type="text" name="phone" id="phone" class="regular-text"></td>
            </tr>

            <tr class="email-wrap">
                <th><label for="email">E-mail <span class="description">(obligatorio)</span></label></th>
                <td><input type="email" name="email" id="email" class="regular-text"></td>
            </tr>

            <tr class="user-display-name-wrap">
                <th>
                    <label for="display_name">Comuna <span class="description">(obligatorio)</span></label>
                </th>
                <td>
                    <select name="display_comuna" id="display_comuna">
                        <option selected="selected"></option>
                        <option id="1240000">Antofagasta</option>
                        <option id="4490000">Antuco</option>
                        <option id="4360000">Arauco</option>
                        <option id="8320000">Santiago</option>
                        <!-- mas opciones -->
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</div>