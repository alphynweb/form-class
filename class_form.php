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
    protected $form_args;
    protected $field_args;
    protected $element;
    protected $html;

    // Constructor
    public function __construct( $form_args, $field_args ) {

        $this->set_form_args( $form_args );
        $this->set_field_args( $field_args );
    }

    // Render form
    public function render_form() {

        $form_args = $this->get_form_args();
        $field_args = $this->get_field_args();

        $this->update_html( '<form' );

        $attributes = array (
            "enctype",
            "method",
            "onsubmit"
        );

        // Render form attributes
        $this->add_attributes( $form_args, $attributes );

        // Render form action attribute
        $this->render_form_action();

        $this->update_html( ' />' );

        // Form message
        $this->render_form_message( $form_args );

        // Fields
        foreach ( $field_args as $args ) {
            // Create label
            $this->label( $args );
            
            // Establish input type
            $this->input_type( $args );
            
        }

        // Form error message
        $this->render_form_error_message( $form_args );

        $this->update_html( '</form>' );

        echo $this->get_html();
    }

    // Render form action
    protected function render_form_action() {
        $form_args = $this->get_form_args();
        $url = null;

        // If no form action supplied, default to _self.
        if ( !isset( $form_args[ 'action' ] ) ) {
            $url = htmlspecialchars( ( $_SERVER[ 'PHP_SELF' ] ) );
        } else {
            $action = $form_args[ 'action' ];

            // self or empty = _self
            if ( strtolower( $action ) == "self" || $action == "" ) {
                $url = htmlspecialchars( ( $_SERVER[ 'PHP_SELF' ] ) );
            } else {
                $url = $action;
            }
        }

        $querystring = isset( $form_args[ 'querystring' ] ) ? "?" . $form_args[ 'querystring' ] : null;

        $action_attribute = array (
            "action" => $url . $querystring
        );

        // Form action
        $this->render_attribute( $action_attribute, 'action' );
    }

    // Render attribute
    protected function render_attribute( $args, $attribute ) {
        // If attribute doesn't exist then return fale
        if ( !isset( $args[ $attribute ] ) ) {
            return false;
        }

        // Render attribute
        $html = ' ' . $attribute . '="' . $args[ $attribute ] . '"';
        $this->update_html( $html );
    }

    // Adds field attributes according to whether field is included in array for that particular attribute
    protected function add_attributes( $args, array $attributes = null ) {
        /* Common attributes */

        // Name
        $this->render_attribute( $args, 'name' );

        // Id
        $this->render_attribute( $args, 'id' );

        // Class
        $this->render_attribute( $args, 'class' );

        if ( $attributes ) {
            foreach ( $attributes as $attribute ) {
                $this->render_attribute( $args, $attribute );
            }
        }
    }

    // Render form message
    protected function render_form_message( $form_args ) {

        if ( !isset( $form_args[ 'message' ] ) ) {
            return false;
        }

        $form_message = $form_args[ 'message' ];

        $this->update_html( '<p>' . $form_message . '</p>' );
    }

    // Render form error message
    protected function render_form_error_message( $form_args ) {

        if ( !isset( $form_args[ 'error' ] ) ) {
            return false;
        }

        $error_message = $form_args[ 'error' ];

        $this->update_html( '<div class="error-message">' . $error_message . '</div>' );
    }

    // Input type
    protected function input_type( $args ) {
        if ( isset( $args[ 'input' ] ) ) {
            $input = $args[ 'input' ];

            // Create field
            switch ( $input ) {
                // Text input
                case "text":
                    $this->text_input( $args );
                    break;
                // Email input
                case "email":
                    $this->email_input( $args );
                    break;
                case "textarea":
                    $this->textarea( $args );
                    break;
                case "radio":
                    $this->radio_group( $args );
                    break;
                case "select":
                    $this->select_dropdown( $args );
                    break;
                case "button":
                    // If type of input is set
                    If ( isset( $args[ 'type' ] ) ) {
                        switch ( $args[ 'type' ] ) {
                            // Submit button
                            case "submit":
                                $this->submit_button( $args );
                                break;
                            // Default button
                            default:
                                $this->button( $args );
                                break;
                        }
                        // If type of input is not set
                    } else {
                        $this->button( $args );
                    }
                    break;
                case "submit":
                    $this->submit_button( $args );
                    break;
                case "password":
                    $this->password_input( $args );
                    break;
                case "link":
                    $this->link_input( $args );
                    break;
                case "hidden":
                    $this->hidden_input( $args );
                    break;
                case "file":
                    $this->file_input( $args );
                    break;
                case "url":
                    $this->url_input( $args );
                    break;
                case "number":
                    $this->number_input( $args );
                    break;
                default:
                    break;
            }
        } else {
            if ( isset( $args[ 'html' ] ) ) {
                $this->update_html( $args[ 'html' ] );
            }
        }
    }

    // Label
    protected function label( $args ) {
        if ( !isset( $args[ 'label' ] ) ) {
            return false;
        }

        $label = $args[ 'label' ];

        // Html
        $this->update_html( '<label' );

        //$this->attribute_for( $args );
        //$this->add_attributes( $args, $attributes );

        $for = null;
        
        if ( isset( $args[ 'id' ] ) ) {
            $for = $args[ 'id' ];
        }
        
        if ($for) {
            $this->update_html(' for="' . $for . '"');
        }

        $this->update_html( '>' );

        $this->update_html( $label );

        $this->update_html( '</label>' );
    }

    // Text input
    protected function text_input( $args ) {

        $attributes = array (
            "disabled",
            "value",
            "placeholder",
            "required"
        );

        // Opening HTML
        $this->update_html( '<input type="text"' );

        // Add attributes
        $this->add_attributes( $args, $attributes );

        // Closing HTML
        $this->update_html( ' />' );
    }

    // Email
    protected function email_input( $args ) {

        $attributes = array (
            "disabled",
            "value",
            "placeholder",
            "required"
        );

        // Html
        $this->update_html( '<input type="email"' );

        // Add attributes
        $this->add_attributes( $args, $attributes );

        // Closing HTML
        $this->update_html( ' />' );
    }

    // Url
    protected function url_input( $args ) {
        $attributes = array (
            "disabled",
            "value",
            "placeholder",
            "required"
        );

        // Html
        $this->update_html( '<input type="url"' );

        // Add attributes
        $this->add_attributes( $args, $attributes );

        // Closing HTML
        $this->update_html( ' />' );
    }

    // Password input
    protected function password_input( $args ) {

        $attributes = array (
            "disabled",
            "value",
            "placeholder",
            "required"
        );

        // Html
        $this->update_html( '<input type="password"' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( ' />' );
    }

    // Number input
    protected function number_input( $args ) {

        $attributes = array (
            "disabled",
            "value",
            "min",
            "max"
        );

        // Html
        $this->update_html( '<input type="number"' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( ' />' );
    }

    // File input
    protected function file_input( $args ) {

        $attributes = array (
            "required"
        );

        $this->update_html( '<input type="file"' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( '>' );
    }

    // Textarea
    protected function textarea( $args ) {

        $attributes = array (
            "required"
        );

        $this->update_html( '<textarea' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( '>' );

        $this->inner_text( $args );

        $this->update_html( '</textarea>' );
    }

    // Inner text
    protected function inner_text( $args ) {
        if ( isset( $args[ 'text' ] ) ) {
            $this->update_html( $args[ 'text' ] );
        }
    }

    // Button
    protected function button( $args ) {

        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<button type="button"' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( '>' );

        $this->attribute_text( $args );

        $this->update_html( '</button>' );
    }

    // Radio group 
    protected function radio_group( $args ) {

        $buttons = isset( $args[ 'buttons' ] ) ? $args[ 'buttons' ] : null;

        // If no buttons stated then return without rendering anything
        if ( !$buttons ) {
            return false;
        }

        $attributes = array (
            "value",
            "checked"
        );

        foreach ( $buttons as $args ) {
            $this->update_html( '<input type="radio"' );

            $this->add_attributes( $args, $attributes );

            $this->update_html( ' />' );
        }
    }

    // Select dropdown
    protected function select_dropdown( $args ) {

        // Get options
        $options = isset( $args[ 'options' ] ) ? $args[ 'options' ] : null;

        if ( !$options ) {
            return false;
        }

        $attributes = array (
                );

        // Html
        $this->update_html( '<select' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( '>' );

        if ( $options ) {
            $attributes = array (
                "value",
                "selected"
            );

            foreach ( $options as $args ) {

                $text = isset( $option[ 'text' ] ) ? $option[ 'text' ] : null;

                $this->update_html( '<option' );

                $this->add_attributes( $args, $attributes );

                $this->update_html( '>' );

                $this->inner_text( $args );

                $this->update_html( '</option>' );
            }
        }

        $this->update_html( '</select>' );
    }

    // Submit button
    protected function submit_button( $args ) {

        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<input type="submit"' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( '>' );

        $this->update_html( '</submit>' );
    }

    // Link
    protected function link_input( $args ) {

        $attributes = array (
            "href"
        );

        // Html
        $this->update_html( '<a' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( '>' );

        $this->inner_text( $args );

        $this->update_html( '</a>' );
    }

    // Hidden input
    protected function hidden_input( $args ) {
        $attributes = array (
            "value"
        );

        // Html
        $this->update_html( '<input type="hidden"' );

        $this->add_attributes( $args, $attributes );

        $this->update_html( '/>' );
    }

    // Update form html
    protected function update_html( $html ) {
        $this->set_html( $this->get_html() . $html );
    }

    /* GETTERS AND SETTERS */

    // Form args
    protected function get_form_args() {
        return $this->form_args;
    }

    protected function set_form_args( $form_args ) {
        $this->form_args = $form_args;
    }

    // Field args
    protected function get_field_args() {
        return $this->field_args;
    }

    protected function set_field_args( $field_args ) {
        $this->field_args = $field_args;
    }

    // Current element
    protected function get_element() {
        return $this->element;
    }

    protected function set_element( $element ) {
        $this->element = $element;
    }

    // Html
    public function get_html() {
        return $this->html;
    }

    protected function set_html( $html ) {
        $this->html = $html;
    }

}
