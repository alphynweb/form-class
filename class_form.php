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
    private $fields;
    private $field;
    private $html;

    // Constructor
    public function __construct( $fields_object ) {
        $this->set_fields( $fields_object );

        foreach ( $fields_object as $field ) {
            $this->set_field( $field );

            // Establish input type
            $this->input_type();

            // Create label
            $this->label();


            $this->set_html( $this->get_html() . "<br />" );
        }
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
                break;
            default:
                break;
        }
    }

    // Label
    private function label() {
//        $field = $this->get_field();
//
//        // If label value is set to false then return
//        if ( isset( $field[ 'label' ] ) && $field[ 'label' ] === false ) {
//            return false;
//        }
        // Label text
        //$label_text = isset( $field[ 'label_text' ] ) ? $field[ 'label_text' ] : null;
        // Label for
        //$label_for = isset( $field[ 'id' ] ) ? $field[ 'id' ] : null;
        // If no label text or label for then don't generate label
//        if ( !$label_text && !$label_for ) {
//            return false;
//        }
        // Html
//        $this->update_html( '<label' );
//
//        $this->field_for();
//
//        $this->update_html( '>' );
//
//        $this->field_text();
//
//        $this->update_html( '</label>' );
    }

    // Text input
    private function text_input() {

        // Html
        $this->update_html( '<input type="text"' );

        $this->field_name();

        $this->field_id();

        $this->field_value();

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

        $this->update_html( '>' );

        $this->field_text();

        $this->update_html( '</submit>' );
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
    private function field_text() {
        $field = $this->get_field();

        // Set field text or null if not supplied
        $text = isset( $field[ 'text' ] ) ? $field[ 'text' ] : null;

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

    // Update form html
    private function update_html( $html ) {
        $this->set_html( $this->get_html() . $html );
    }

    // Getters and setters
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
