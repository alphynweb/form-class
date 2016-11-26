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

        // Form message
        $this->form_message( $form_args );

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

    private function render_attribute( $element, $attribute ) {
        // If attribute doesn't exist then return fale
        if ( !isset( $element[ $attribute ] ) ) {
            return false;
        }

        // Render attribute
        $html = ' ' . $attribute . '="' . $element[ $attribute ] . '"';
        $this->update_html( $html );
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
                $this->render_attribute($element, $attribute);
            }
        }
    }

    // Render form
    public function render_form() {
        echo $this->get_html();
    }

    private function form_message( $element ) {

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
            case "number":
                $this->number_input( $element );
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
            "placeholder",
            "required"
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
            "placeholder",
            "required"
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
            "placeholder",
            "required"
        );

        // Html
        $this->update_html( '<input type="password"' );

        $this->add_attributes( $attributes );

        $this->update_html( ' />' );
    }

    private function number_input( $element ) {

        $attributes = array (
            "disabled",
            "value",
            "min",
            "max"
        );

        // Html
        $this->update_html( '<input type="number"' );

        $this->add_attributes( $attributes );

        $this->update_html( ' />' );
    }

    // File input
    private function file_input( $element ) {

        $attributes = array (
            "required"
        );

        $this->update_html( '<input type="file"' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );
    }

    // Textarea
    private function textarea( $element ) {

        $attributes = array (
            "required"
        );

        $this->update_html( '<textarea' );

        $this->add_attributes( $attributes );

        $this->update_html( '>' );

        if ( isset( $element[ 'text' ] ) ) {

            $this->attribute_text( $element );
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

                $this->attribute_text( $option );

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
