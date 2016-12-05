<?php

class ExtendedForm extends form
{

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

        $this->update_html( '<h1>Extended</h1>' );

        echo $this->get_html();
    }

}
