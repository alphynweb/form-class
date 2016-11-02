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

        $attributes = array (
            "action",
            "enctype",
            "method",
            "querystring"
        );

        $this->add_attributes( $attributes );

//        if ( isset( $form_object[ 'id' ] ) ) {
//            $this->attribute_id();
//        }
//
//        if ( isset( $form_object[ 'name' ] ) ) {
//            $this->attribute_name();
//        }
//
//        $this->attribute_method();
//
//        $this->attribute_action();
//
//        $this->attribute_enctype();

        $this->update_html( ' />' );

        //$this->attribute_message();

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
    public function render_form() {
        echo $this->get_html();
    }

    // Form method
    private function attribute_method() {
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

    private function attribute_action() {
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

    private function attribute_querystring() {
        $querystring = isset( $element[ 'querystring' ] ) ? "?" . $element[ 'querystring' ] : null;

        if ( $querystring ) {
            $this->update_html( '?' . $querystring );
        }
    }

    private function attribute_enctype() {
        $form = $this->get_form();

        if ( !isset( $form[ 'enctype' ] ) ) {
            return false;
        }

        $this->update_html( ' enctype="' . $form[ 'enctype' ] . '"' );
    }

    private function attribute_message() {
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

    // Adds field attributes according to whether field is included in array for that particular attribute
    private function add_attributes( array $attributes = null ) {
        // Name
        $this->attribute_name();

        // Id
        $this->attribute_id();

        // Class
        $this->attribute_class();

        if ( $attributes ) {
            foreach ( $attributes as $attribute ) {
                $func_name = "attribute_" . $attribute;
                $this->$func_name();
            }
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
                $this->submit_button();
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
            case "file":
                $this->file_input();
                break;
            case "url":
                $this->url_input();
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

        // Html
        $this->update_html( '<label' );

        $this->attribute_for( $field[ 'label' ] );

        $this->update_html( '>' );

        $this->attribute_text( $field[ 'label' ] );

        $this->update_html( '</label>' );
    }

    // Text input
    private function text_input() {

        $attributes = array (
            "disabled",
            "value",
            "placeholder"
        );

        // Opening HTML
        $this->update_html( '<input type="text"' );

        // Add attributes
        $this->add_attributes( $attributes );

        // Closing HTML
        $this->update_html( ' />' );
    }

    // Email
    private function email_input() {

        $attributes = array (
            "disabled",
            "value",
            "placeholder"
        );

        // Html
        $this->update_html( '<input type="email"' );

        // Add attributes
        $this->add_attributes( $attributes );

        // Closing HTML
        $this->update_html( ' />' );
    }

    // Url
    private function url_input() {
        $attributes = array (
            "disabled",
            "value",
            "placeholder"
        );

        // Html
        $this->update_html( '<input type="url"' );

        // Add attributes
        $this->add_attributes( $attributes );

        // Closing HTML
        $this->update_html( ' />' );
    }

    // Password input
    private function password_input() {

        $attributes = array (
            "disabled",
            "value",
            "placeholder"
        );

        // Html
        $this->update_html( '<input type="password"' );

        $this->add_attributes( $attributes );

        $this->update_html( ' />' );
    }

    // File input
    private function file_input() {
        $field = $this->get_field();

        $this->update_html( '<input type="file"' );

        $this->add_attributes();

        $this->update_html( '>' );
    }

    // Textarea
    private function textarea() {
        $attributes = array (
                );

        $this->update_html( '<textarea' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        $this->attribute_text();

        $this->update_html( '</textarea>' );
    }

    // Button
    private function button() {

        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<button type="button"' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        $this->attribute_text();

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

            $this->add_attributes();

            $this->update_html( ' />' );
        }
    }

    // Select dropdown
    private function select_dropdown() {

        $field = $this->get_field();

        // Get options
        $options = isset( $field[ 'options' ] ) ? $field[ 'options' ] : null;

        if ( !$options ) {
            return false;
        }

        $attributes = array (
                );

        // Html
        $this->update_html( '<select' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );


//        if ( $options ) {
//            foreach ( $options as $key => $value ) {
//                $this->update_html( '<option value="' . $value . '">' . $key . '</option>' );
//            }
//        }

        if ( $options ) {
            $attributes = array (
                "value",
                "selected"
            );

            foreach ( $options as $option ) {
                //$this->update_html( '<option value="' . $value . '">' . $key . '</option>' );

                $text = isset( $option[ 'text' ] ) ? $option[ 'text' ] : null;

                $this->update_html( '<option' );

                $this->add_attributes( $attributes );

                $this->update_html( '>' );

                $this->attribute_text( $text );

                $this->update_html( '</option>' );
            }
        }

        $this->update_html( '</select>' );
    }

    // Submit button
    private function submit_button() {

        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<input type="submit"' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        $this->attribute_text();

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

        $attributes = array (
            "href"
        );

        // Html
        $this->update_html( '<a' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        $this->attribute_text();

        $this->update_html( '</a>' );
    }

    private function hidden_input() {
        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<input type="hidden"' );

        $this->add_attributes( $attributes );

        $this->update_html( '/>' );
    }

    private function attribute_type() {
        $field = $this->get_field();

        // Set field type or null if not supplied
        $type = isset( $field[ 'type' ] ) ? $field[ 'type' ] : null;

        if ( $type ) {
            $html = ' type="' . $type . '"';
            $this->update_html( $html );
        }
    }

    // Generate field name
    private function attribute_name() {
        $field = $this->get_field();

        // Set field name or null if not supplied
        $name = isset( $field[ 'name' ] ) ? $field[ 'name' ] : null;

        if ( $name ) {
            $html = ' name="' . $name . '"';
            $this->update_html( $html );
        }
    }

    // Generate field id
    private function attribute_id() {
        $field = $this->get_field();

        // Set field id or null if not supplied
        $id = isset( $field[ 'id' ] ) ? $field[ 'id' ] : null;

        if ( $id ) {
            $html = ' id="' . $id . '"';
            $this->update_html( $html );
        };
    }

    // Generate field value
    private function attribute_value() {
        $field = $this->get_field();

        // Set field id or null if not supplied
        $value = isset( $field[ 'value' ] ) ? $field[ 'value' ] : null;

        if ( $value ) {
            $html = ' value="' . $value . '"';
            $this->update_html( $html );
        }
    }

    // Generate fiedl placeholder
    private function attribute_placeholder() {
        $field = $this->get_field();

        // Set field id or null if not supplied
        $placeholder = isset( $field[ 'placeholder' ] ) ? $field[ 'placeholder' ] : null;

        if ( $placeholder ) {
            $html = ' placeholder="' . $placeholder . '"';
            $this->update_html( $html );
        }
    }

    // Generate field text
    private function attribute_text( $text = null ) {
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
    private function attribute_for() {
        $field = $this->get_field();

        // Set field for to equal id or null if field does not have id
        $attribute_for = isset( $field[ 'id' ] ) ? $field[ 'id' ] : null;

        if ( !empty( $attribute_for ) ) {
            $html = ' for="' . $attribute_for . '"';
            $this->update_html( $html );
        }
    }

    private function attribute_checked( $field ) {
        $checked = isset( $field[ 'checked' ] ) ? $field[ 'checked' ] : false;

        if ( $checked ) {
            $this->update_html( ' checked="checked"' );
        }
    }

    private function attribute_disabled() {
        $field = $this->get_field();

        $disabled = isset( $field[ 'disabled' ] ) ? $field[ 'disabled' ] : false;

        if ( $disabled ) {
            $this->update_html( ' disabled="disabled"' );
        }
    }

    private function attribute_selected( $selected = null ) {
        // If no value passed through for selected then get it from the field
        if ( !$selected ) {
            $field = $this->get_field();
            $selected = isset( $field[ 'selected' ] ) ? $field[ 'selected' ] : false;
        }

        if ( $selected ) {
            $this->update_html( ' selected="selected' );
        }
    }

    private function attribute_href() {
        $field = $this->get_field();

        $href = isset( $field[ 'href' ] ) ? $field[ 'href' ] : false;

        if ( $href ) {
            $this->update_html( ' href="' . $href . '"' );
        }
    }

    private function attribute_class() {
        $field = $this->get_field();

        $class = isset( $field[ 'class' ] ) ? $field[ 'class' ] : false;

        if ( $class ) {
            $this->update_html( ' class="' . $class . '"' );
        }
    }

    private function attribute_readonly() {
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
