<?php

// 'label' - false or a value. Overrides label being same as name
// 'name' - name of input
// 'id' - id of input
// 'placeholder_text' - placeholder text
// 'value' - initial value of input
// 'type' - type of input. 'text', 'email', 'radio', 'select', 'button', 'submit', 'passsword'

class Form
{

    // Properties
    // Object with info about form fields
    private $form;
    private $fields;
    private $field;
    private $html;

    // Constructor
    public function __construct( $form_object, $fields_object ) {

        $this->set_form( $form_object );

        $this->update_html( '<form' );

        if ( isset( $form_object[ 'id' ] ) ) {
            $this->formId();
        }

        if ( isset( $form_object[ 'name' ] ) ) {
            $this->formName();
        }

        $this->formMethod();

        $this->formAction();
        
        $this->formEnctype();

        $this->update_html( ' />' );

        $this->formMessage();

        $this->set_fields( $fields_object );

        foreach ( $fields_object as $field ) {

            // If the field 
            $this->set_field( $field );

            // Create label
            $this->label();

            // Establish input type
            $this->input_type();

            $this->set_html( $this->get_html() );
        }

        $this->formErrorMessage();

        $this->update_html( '</form>' );
    }

    // Render form
    public function renderForm() {
        echo $this->get_html();
    }

    // Form Id
    private function formId() {
        $form = $this->get_form();

        if ( !isset( $form[ 'id' ] ) ) {
            return false;
        }

        $this->update_html( ' id="' . $form[ 'id' ] . '"' );
    }

    // Form name
    private function formName() {
        $form = $this->get_form();

        if ( !isset( $form[ 'name' ] ) ) {
            return false;
        }

        $this->update_html( ' name="' . $form[ 'name' ] . '"' );
    }

    // Form method
    private function formMethod() {
        $form = $this->get_form();

        if ( !isset( $form[ 'method' ] ) ) {
            // Default to get if no method supplied
            $this->update_html( ' method="get"' );
            return true;
        }

        $form_method = strtolower( $form[ 'method' ] );

        if ( $form_method != "post" && $form_method != "get" ) {
            return false;
        }

        $this->update_html( ' method="' . $form_method . '"' );
    }

    private function formAction() {
        $form = $this->get_form();

        $querystring = isset( $form[ 'querystring' ] ) ? "?" . $form[ 'querystring' ] : null;

        // If no form action supplied, default to _self.
        if ( !isset( $form[ 'action' ] ) ) {
            $this->update_html( ' action="' . htmlspecialchars( $_SERVER[ "PHP_SELF" ] ) . $querystring . '"' );
            return true;
        }

        $form_action = $form[ 'action' ];

        // self or empty = _self
        if ( strtolower( $form_action ) == "self" || $form_action == "" ) {
            $this->update_html( ' action="' . htmlspecialchars( $_SERVER[ "PHP_SELF" ] ) . $querystring . '"' );
            return true;
        }

        $this->update_html( ' action="' . $form_action . $querystring . '"' );
    }
    
    private function formEnctype() {
        $form = $this->get_form();
        
        if ( !isset( $form[ 'enctype' ] ) ) {
            return false;
        }

        $this->update_html( ' enctype="' . $form[ 'enctype' ] . '"' );
    }

    private function formMessage() {
        $form = $this->get_form();

        if ( !isset( $form[ 'message' ] ) ) {
            return false;
        }

        $form_message = $form[ 'message' ];

        $this->update_html( '<p>' . $form_message . '</p>' );
    }

    private function formErrorMessage() {
        $form = $this->get_form();

        if ( !isset( $form[ 'error' ] ) ) {
            return false;
        }

        $error_message = $form[ 'error' ];

        $this->update_html( '<div class="error-message">' . $error_message . '</div>' );
    }

    // Input type
    private function input_type() {
        $field = $this->get_field();
        $input = $field[ 'input' ];

        // Create field
        switch ( $input ) {
            // Text input
            case "text":
                $this->text_input();
                break;
            // Email input
            case "email":
                $this->email_input();
                break;
            case "textarea":
                $this->textarea();
                break;
            case "radio":
                $this->radio_group();
                break;
            case "select":
                $this->select_dropdown();
                break;
            case "button":
                // If type of input is set
                If ( isset( $field[ 'type' ] ) ) {
                    switch ( $field[ 'type' ] ) {
                        // Submit button
                        case "submit":
                            $this->submit_button();
                            break;
                        // Default button
                        default:
                            $this->button();
                            break;
                    }
                    // If type of input is not set
                } else {
                    $this->button();
                }
                break;
            case "submit":
                $this->submit();
                break;
            case "password":
                $this->password_input();
                break;
            case "link":
                $this->link_input();
                break;
            case "hidden":
                $this->hidden_input();
                break;
            default:
                break;
        }
    }

    // Label
    private function label() {
        // Label will not show if :
        // No label text
        // label = false
        // Label "for" will show if the labelled element has an id

        $field = $this->get_field();

        // If label isn't set then return
        if ( !isset( $field[ 'label' ] ) ) {
            return false;
        }

        // If label value is set to false or empty then return
        if ( isset( $field[ 'label' ] ) && ($field[ 'label' ] === false || empty( $field[ 'label' ] )) ) {
            return false;
        }

        // Label text
        $label_text = $field[ 'label' ];

        // Label for
        $label_for = isset( $field[ 'id' ] ) ? $field[ 'id' ] : null;

        // Html
        $this->update_html( '<label' );

        if ( $label_for ) {
            $this->field_for();
        }

        $this->update_html( '>' );

        $this->field_text( $label_text );

        $this->update_html( '</label>' );
    }

    // Text input
    private function text_input() {

        // Html
        $this->update_html( '<input type="text"' );

        $this->field_name();

        $this->field_id();

        $this->field_value();

        $this->field_readonly();

        $this->field_placeholder();

        $html = $this->get_html() . '/>';

        $this->set_html( $html );
    }

    // Email
    private function email_input() {

        // Html
        $this->update_html( '<input type="email"' );

        $this->field_name();

        $this->field_id();

        $this->field_value();

        $this->field_placeholder();

        $this->update_html( '/>' );
    }

    // Password input
    private function password_input() {

        // Html
        $this->update_html( '<input type="password"' );

        $this->field_name();

        $this->field_id();

        $this->field_value();

        $this->field_placeholder();

        $this->update_html( '/>' );
    }

    // Textarea
    private function textarea() {
        $field = $this->get_field();

        $this->update_html( '<textarea' );

        $this->field_name();

        $this->field_id();

        $this->update_html( '>' );

        $this->field_text();

        $this->update_html( '</textarea>' );
    }

    // Button
    private function button() {

        $field = $this->get_field();

        $type = isset( $field[ 'type' ] ) ? $field[ 'type' ] : null;

        // Html
        $this->update_html( '<button type="button"' );

        $this->field_type();

        $this->field_name();

        $this->field_id();

        $this->field_value();

        $this->update_html( '>' );

        $this->field_text();

        $this->update_html( '</button>' );
    }

    // Radio group 
    private function radio_group() {
        $field = $this->get_field();

        $buttons = isset( $field[ 'buttons' ] ) ? $field[ 'buttons' ] : null;

        // If no buttons stated then return without rendering anything
        if ( !$buttons ) {
            return false;
        }

        foreach ( $buttons as $button ) {
            $this->update_html( '<input type="radio"' );

            $this->field_name();
            $this->field_checked( $button );
            $this->field_disabled( $button );

            $this->update_html( ' />' );
        }
    }

    // Select dropdown
    private function select_dropdown() {
        $field = $this->get_field();

        // Html
        $this->update_html( '<select>' );

        // Get options
        $options = $field[ 'options' ];

        foreach ( $options as $key => $value ) {
            $this->update_html( '<option value="' . $value . '">' . $key . '</option>' );
        }

        $this->update_html( '</select>' );
    }

    // Submit button
    private function submit_button() {
        $field = $this->get_field();

        $type = isset( $field[ 'type' ] ) ? $field[ 'type' ] : null;

        // Html
        $this->update_html( '<input type="submit"' );

        $this->field_type();

        $this->field_name();

        $this->field_id();

        $this->field_value();

        $this->update_html( '>' );

        $this->field_text();

        $this->update_html( '</submit>' );
    }

    private function link_input() {
        $field = $this->get_field();

        // If no text or href set then return
        if ( !isset( $field[ 'text' ] ) || !isset( $field[ 'href' ] ) ) {
            return false;
        }

        // If text or href are empty then return
        if ( empty( $field[ 'text' ] ) || empty( $field[ 'href' ] ) ) {
            return false;
        }

        $text = $field[ 'text' ];
        $href = $field[ 'href' ];

        // Html
        $this->update_html( '<a' );

        $this->field_href();

        $this->field_class();

        $this->update_html( '>' );

        $this->field_text();

        $this->update_html( '</a>' );
    }

    private function hidden_input() {
        // Html
        $this->update_html( '<input type="hidden"' );

        $this->field_name();

        $this->field_id();

        $this->field_value();

        $this->update_html( '/>' );
    }

    private function field_type() {
        $field = $this->get_field();

        // Set field type or null if not supplied
        $type = isset( $field[ 'type' ] ) ? $field[ 'type' ] : null;

        if ( $type ) {
            $html = ' type="' . $type . '"';
            $this->update_html( $html );
        }
    }

    // Generate field name
    private function field_name() {
        $field = $this->get_field();

        // Set field name or null if not supplied
        $name = isset( $field[ 'name' ] ) ? $field[ 'name' ] : null;

        if ( $name ) {
            $html = ' name="' . $name . '"';
            $this->update_html( $html );
        }
    }

    // Generate field id
    private function field_id() {
        $field = $this->get_field();

        // Set field id or null if not supplied
        $id = isset( $field[ 'id' ] ) ? $field[ 'id' ] : null;

        if ( $id ) {
            $html = ' id="' . $id . '"';
            $this->update_html( $html );
        };
    }

    // Generate field value
    private function field_value() {
        $field = $this->get_field();

        // Set field id or null if not supplied
        $value = isset( $field[ 'value' ] ) ? $field[ 'value' ] : null;

        if ( $value ) {
            $html = ' value="' . $value . '"';
            $this->update_html( $html );
        }
    }

    // Generate fiedl placeholder
    private function field_placeholder() {
        $field = $this->get_field();

        // Set field id or null if not supplied
        $placeholder = isset( $field[ 'placeholder' ] ) ? $field[ 'placeholder' ] : null;

        if ( $placeholder ) {
            $html = ' placeholder="' . $placeholder . '"';
            $this->update_html( $html );
        }
    }

    // Generate field text
    private function field_text( $text = null ) {
        $field = $this->get_field();

        // Text is supplied text, or if not supplied it looks for text value on field array
        if ( !$text ) {
            // Set field text or null if not supplied
            $text = isset( $field[ 'text' ] ) ? $field[ 'text' ] : null;
        }

        if ( $text ) {
            $html = $text;
            $this->update_html( $html );
        }
    }

    // Generate field for
    private function field_for() {
        $field = $this->get_field();

        // Set field for to equal id or null if field does not have id
        $field_for = isset( $field[ 'id' ] ) ? $field[ 'id' ] : null;

        if ( !empty( $field_for ) ) {
            $html = ' for="' . $field_for . '"';
            $this->update_html( $html );
        }
    }

    private function field_checked( $field ) {
        $checked = isset( $field[ 'checked' ] ) ? $field[ 'checked' ] : false;

        if ( $checked ) {
            $this->update_html( ' checked="checked"' );
        }
    }

    private function field_disabled( $field ) {
        $disabled = isset( $field[ 'disabled' ] ) ? $field[ 'disabled' ] : false;

        if ( $disabled ) {
            $this->update_html( ' disabled="disabled"' );
        }
    }

    private function field_href() {
        $field = $this->get_field();

        $href = isset( $field[ 'href' ] ) ? $field[ 'href' ] : false;

        if ( $href ) {
            $this->update_html( ' href="' . $href . '"' );
        }
    }

    private function field_class() {
        $field = $this->get_field();

        $class = isset( $field[ 'class' ] ) ? $field[ 'class' ] : false;

        if ( $class ) {
            $this->update_html( ' class="' . $class . '"' );
        }
    }

    private function field_readonly() {
        $field = $this->get_field();

        if ( !isset( $field[ 'readonly' ] ) ) {
            return false;
        }

        if ( $field[ 'readonly' ] == true ) {
            $this->update_html( ' readonly="readonly"' );
        }
    }

    // Update form html
    private function update_html( $html ) {
        $this->set_html( $this->get_html() . $html );
    }

    // GETTERS AND SETTERS
    private function get_form() {
        return $this->form;
    }

    private function set_form( $form_object ) {
        $this->form = $form_object;
    }

    private function get_fields() {
        return $this->fields;
    }

    private function set_fields( $fields_object ) {
        $this->fields = $fields_object;
    }

    private function get_field() {
        return $this->field;
    }

    private function set_field( $field ) {
        $this->field = $field;
    }

    public function get_html() {
        return $this->html;
    }

    private function set_html( $html ) {
        $this->html = $html;
    }

}
