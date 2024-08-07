<?php

namespace App\Helpers;

class HtmlHelper {
    public static function getRowHtml($contact, $append = false) {
        if ($append) {
            $html = '<tbody hx-swap-oob="beforeend:#contacts-body">';
        }
    
        $html ??= "";
    
        $html .= <<<HTML
            <tr>
                <td>{$contact['name']}</td>
                <td>{$contact['email']}</td>
                <td>
                    <button class="uk-button uk-button-default" hx-get="/contact/{$contact['id']}/edit">Edit</button>
                    <button class="uk-button uk-button-default" hx-delete="/contact/{$contact['id']}" hx-confirm="Are you sure?">Delete</button>
                </td>
            </tr>
        HTML;
    
        if ($append) {
            $html .= "</tbody>";
        }
    
        return $html;
    }
    
    public static function getEditRowHtml($contact) {
        return <<<HTML
            <tr hx-trigger="cancel" class="editing" hx-get="/contact/{$contact['id']}">
                <td>
                    <input class="uk-input" type="text" value="{$contact['name']}" name="name">
                </td>
                <td>
                    <input class="uk-input" type="text" value="{$contact['email']}" name="email">
                </td>
                <td>
                    <button class="uk-button uk-button-default" hx-get="/contact/{$contact['id']}">Cancel</button>
                    <button class="uk-button uk-button-default" hx-put="/contact/{$contact['id']}" hx-include="closest tr" hx-trigger="click[checkFormState(this)]">Save</button>
                </td>
            </tr>
        HTML;
    }
    
}