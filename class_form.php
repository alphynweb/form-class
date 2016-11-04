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
    private $element;
    private $html;

    // Constructor
    public function __construct( $form_args, $field_args ) {

        // Form
        $this->set_element( $form_args );

        $this->update_html( '<form' );

        $attributes = array (
            "action",
            "enctype",
            "method",
            "onsubmit"
        );

        $this->add_attributes( $attributes );

        $this->update_html( ' />' );

        // Fields
        foreach ( $field_args as $element ) {

            $this->set_element( $element );

            // Create label
            $this->label( $element );

            // Establish input type
            $this->input_type( $element );
        }

        // Form error message
        $this->formErrorMessage( $form_args );

        $this->update_html( '</form>' );
    }

    // Adds field attributes according to whether field is included in array for that particular attribute
    private function add_attributes( array $attributes = null ) {
        $element = $this->get_element();
        // Name
        $this->attribute_name( $element );

        // Id
        $this->attribute_id( $element );

        // Class
        $this->attribute_class( $element );

        if ( $attributes ) {
            foreach ( $attributes as $attribute ) {
                $func_name = "attribute_" . $attribute;
                $this->$func_name( $element );
            }
        }
    }

    // Render form
    public function render_form() {
        echo $this->get_html();
    }

    // Form method
    private function attribute_method( $element ) {
        $form = $this->get_form( $element );

        if ( !isset( $element[ 'method' ] ) ) {
            // Default to get if no method supplied
            $this->update_html( ' method="get"' );
            return true;
        }

        $form_method = strtolower( $element[ 'method' ] );

        if ( $form_method != "post" && $form_method != "get" ) {
            return false;
        }

        $this->update_html( ' method="' . $form_method . '"' );
    }

    private function attribute_action( $element ) {

        $querystring = isset( $element[ 'querystring' ] ) ? "?" . $element[ 'querystring' ] : null;

        // If no form action supplied, default to _self.
        if ( !isset( $element[ 'action' ] ) ) {
            $this->update_html( ' action="' . htmlspecialchars( $_SERVER[ "PHP_SELF" ] ) . $querystring . '"' );
            return true;
        }

        $form_action = $element[ 'action' ];

        // self or empty = _self
        if ( strtolower( $form_action ) == "self" || $form_action == "" ) {
            $this->update_html( ' action="' . htmlspecialchars( $_SERVER[ "PHP_SELF" ] ) . $querystring . '"' );
            return true;
        }

        $this->update_html( ' action="' . $form_action . $querystring . '"' );
    }

//    private function attribute_querystring( $element ) {
//        $querystring = isset( $element[ 'querystring' ] ) ? "?" . $element[ 'querystring' ] : null;
//
//        if ( $querystring ) {
//            $this->update_html( '?' . $querystring );
//        }
//    }

    private function attribute_enctype( $element ) {

        if ( !isset( $element[ 'enctype' ] ) ) {
            return false;
        }

        $this->update_html( ' enctype="' . $element[ 'enctype' ] . '"' );
    }

    private function attribute_message( $element ) {

        if ( !isset( $element[ 'message' ] ) ) {
            return false;
        }

        $form_message = $element[ 'message' ];

        $this->update_html( '<p>' . $form_message . '</p>' );
    }

    private function formErrorMessage( $element ) {

        if ( !isset( $element[ 'error' ] ) ) {
            return false;
        }

        $error_message = $element[ 'error' ];

        $this->update_html( '<div class="error-message">' . $error_message . '</div>' );
    }

    // Input type
    private function input_type( $element ) {
        $input = $element[ 'input' ];

        // Create field
        switch ( $input ) {
            // Text input
            case "text":
                $this->text_input( $element );
                break;
            // Email input
            case "email":
                $this->email_input( $element );
                break;
            case "textarea":
                $this->textarea( $element );
                break;
            case "radio":
                $this->radio_group( $element );
                break;
            case "select":
                $this->select_dropdown( $element );
                break;
            case "button":
                // If type of input is set
                If ( isset( $element[ 'type' ] ) ) {
                    switch ( $element[ 'type' ] ) {
                        // Submit button
                        case "submit":
                            $this->submit_button( $element );
                            break;
                        // Default button
                        default:
                            $this->button( $element );
                            break;
                    }
                    // If type of input is not set
                } else {
                    $this->button( $element );
                }
                break;
            case "submit":
                $this->submit_button( $element );
                break;
            case "password":
                $this->password_input( $element );
                break;
            case "link":
                $this->link_input( $element );
                break;
            case "hidden":
                $this->hidden_input( $element );
                break;
            case "file":
                $this->file_input( $element );
                break;
            case "url":
                $this->url_input( $element );
                break;
            default:
                break;
        }
    }

    // Label
    private function label( $element ) {
        // Label will not show if :
        // No label text
        // label = false
        // Label "for" will show if the labelled element has an id
        // If label isn't set then return
        // If label value is set to false or empty then return
        $label = isset( $element[ 'label' ] ) ? $element[ 'label' ] : null;

        if ( !$label ) {
            return false;
        }

        // Html
        $this->update_html( '<label' );

        $this->attribute_for( $element );

        $this->update_html( '>' );

        $this->update_html( $label );

        $this->update_html( '</label>' );
    }

    // Text input
    private function text_input( $element ) {

        $attributes = array (
            "disabled",
            "value",
            "placeholder",
            "required"
        );

        // Opening HTML
        $this->update_html( '<input type="text"' );

        // Add attributes
        $this->add_attributes( $attributes );

        // Closing HTML
        $this->update_html( ' />' );
    }

    // Email
    private function email_input( $element ) {

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
    private function url_input( $element ) {
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
    private function password_input( $element ) {

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
    private function file_input( $element ) {

        $attributes = array (
                );

        $this->update_html( '<input type="file"' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );
    }

    // Textarea
    private function textarea( $element ) {

        $attributes = array (
                );

        $this->update_html( '<textarea' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        if ( isset( $element[ 'text' ] ) ) {

            $this->attribute_text( $element[ 'text' ] );
        }

        $this->update_html( '</textarea>' );
    }

    // Button
    private function button( $element ) {

        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<button type="button"' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        $this->attribute_text( $element );

        $this->update_html( '</button>' );
    }

    // Radio group 
    private function radio_group( $element ) {

        $buttons = isset( $element[ 'buttons' ] ) ? $element[ 'buttons' ] : null;

        // If no buttons stated then return without rendering anything
        if ( !$buttons ) {
            return false;
        }

        $attributes = array (
            "value",
            "checked"
        );

        foreach ( $buttons as $button ) {
            $this->set_element( $button );

            $this->update_html( '<input type="radio"' );

            $this->add_attributes( $attributes );

            $this->update_html( ' />' );
        }
    }

    // Select dropdown
    private function select_dropdown( $element ) {

        // Get options
        $options = isset( $element[ 'options' ] ) ? $element[ 'options' ] : null;

        if ( !$options ) {
            return false;
        }

        $attributes = array (
                );

        // Html
        $this->update_html( '<select' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        if ( $options ) {
            $attributes = array (
                "value",
                "selected"
            );

            foreach ( $options as $option ) {

                $this->set_element( $option );

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
    private function submit_button( $element ) {

        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<input type="submit"' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        $this->update_html( '</submit>' );
    }

    private function link_input( $element ) {

        $attributes = array (
            "href"
        );

        // Html
        $this->update_html( '<a' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        $this->attribute_text( $element );

        $this->update_html( '</a>' );
    }

    private function hidden_input( $element ) {
        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<input type="hidden"' );

        $this->add_attributes( $attributes );

        $this->update_html( '/>' );
    }

    private function attribute_onsubmit( $element ) {
        $onsubmit = isset( $element[ 'onsubmit' ] ) ? $element[ 'onsubmit' ] : null;

        $this->update_html( ' onsubmit="' . $onsubmit . '()"' );
    }

    private function attribute_type( $element ) {

        // Set field type or null if not supplied
        $type = isset( $element[ 'type' ] ) ? $element[ 'type' ] : null;

        if ( $type ) {
            $html = ' type="' . $type . '"';
            $this->update_html( $html );
        }
    }

    // Generate field name
    private function attribute_name( $element ) {

        // Set field name or null if not supplied
        $name = isset( $element[ 'name' ] ) ? $element[ 'name' ] : null;

        if ( $name ) {
            $html = ' name="' . $name . '"';
            $this->update_html( $html );
        }
    }

    // Generate field id
    private function attribute_id( $element ) {

        // Set field id or null if not supplied
        $id = isset( $element[ 'id' ] ) ? $element[ 'id' ] : null;

        if ( $id ) {
            $html = ' id="' . $id . '"';
            $this->update_html( $html );
        };
    }

    // Generate field value
    private function attribute_value( $element ) {

        // Set field id or null if not supplied
        $value = isset( $element[ 'value' ] ) ? $element[ 'value' ] : null;

        if ( $value ) {
            $html = ' value="' . $value . '"';
            $this->update_html( $html );
        }
    }

    // Generate field placeholder
    private function attribute_placeholder( $element ) {
        // Set field id or null if not supplied
        $placeholder = isset( $element[ 'placeholder' ] ) ? $element[ 'placeholder' ] : null;

        if ( $placeholder ) {
            $html = ' placeholder="' . $placeholder . '"';
            $this->update_html( $html );
        }
    }

    // Generate "required" attribute
    private function attribute_required( $element ) {

        if ( !isset( $element[ 'required' ] ) ) {
            return false;
        }

        if ( $element[ 'required' ] === true ) {
            $this->update_html( ' required' );
        }
    }

    // Generate field text
    private function attribute_text( $element ) {

        $text = isset( $element[ 'text' ] ) ? $element[ 'text' ] : null;

        if ( !$text ) {
            return false;
        }

        $this->update_html( $text );
    }

    // Generate field for
    private function attribute_for( $element ) {

        // Set field for to equal id or null if field does not have id
        $attribute_for = isset( $element[ 'id' ] ) ? $element[ 'id' ] : null;

        if ( !empty( $attribute_for ) ) {
            $html = ' for="' . $attribute_for . '"';
            $this->update_html( $html );
        }
    }

    private function attribute_checked( $element ) {

        if ( !isset( $element[ 'checked' ] ) ) {
            return false;
        }

        if ( $element[ 'checked' ] === true ) {
            $this->update_html( ' checked' );
        }
    }

    private function attribute_disabled( $element ) {

        if ( !isset( $element[ 'disabled' ] ) ) {
            return false;
        }

        if ( $element[ 'disabled' ] === true ) {
            $this->update_html( ' disabled' );
        }
    }

    private function attribute_selected( $element ) {

        if ( !isset( $element[ 'selected' ] ) ) {
            return false;
        }

        if ( $element[ 'selected' ] === true ) {
            $this->update_html( ' selected' );
        }
    }

    private function attribute_href( $element ) {

        $href = isset( $element[ 'href' ] ) ? $element[ 'href' ] : false;

        if ( $href ) {
            $this->update_html( ' href="' . $href . '"' );
        }
    }

    private function attribute_class( $element ) {

        $class = isset( $element[ 'class' ] ) ? $element[ 'class' ] : false;

        if ( $class ) {
            $this->update_html( ' class="' . $class . '"' );
        }
    }

    private function attribute_readonly( $element ) {

        if ( !isset( $element[ 'readonly' ] ) ) {
            return false;
        }

        if ( $element[ 'readonly' ] === true ) {
            $this->update_html( ' readonly' );
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

    private function get_elements() {
        return $this->fields;
    }

    private function set_elements( $fields_object ) {
        $this->elements = $fields_object;
    }

    private function get_element() {
        return $this->element;
    }

    private function set_element( $element ) {
        $this->element = $element;
    }

    public function get_html() {
        return $this->html;
    }

    private function set_html( $html ) {
        $this->html = $html;
    }

}
