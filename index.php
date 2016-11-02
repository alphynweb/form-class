<!DOCTYPE html>
<html>
    <head>
        <?php require_once 'class_form.php'; ?>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            form input {
                display: block;
                margin-bottom: 2rem;
            }
        </style>
    </head>
    <body>
        <?php
        // Tester - three fields
        $form = array (
            "message" => "This is a sample message for the form",
            "id" => "formId",
            "name" => "formName",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "querystring" => "t=10&r=20"
        );
        $fields = array (
            // File
            array (
                "input" => "file",
                "name" => "file_input",
                "id" => "file_input"
            ),
//            // Text input one
            array (
                "input" => "text",
                "name" => "text_attribute_one",
                "id" => "textFieldOne",
                "value" => "Text box one value",
                "label" => "Text box one label",
                "placeholder" => "Text box placeholder"
            ),
////            // Text input two
            array (
                "input" => "email",
                "name" => "email",
                "placeholder" => "Please enter your email here"
            ),
//////            // Password input
            array (
                "input" => "password",
                "name" => "password",
                "placeholder" => "Enter your password here"
            ),
////            // Textarea
            array (
                "input" => "textarea",
                "text" => "Test text for textarea"
            ),
////            // Button
            array (
                "input" => "submit",
////                "name" => "submit"
            ),
            array (
                "input" => "url",
                "placeholder" => "Enter url",
                "label" => "URL goes here"
            ),
            array (
                "input" => "select",
                "options" => array(
                    array(
                        "text" => "Option one text",
                        "value" => "1"
                    ),
                    array(
                        "text" => "Option two text",
                        "value" => "2",
                        "selected" => true
                    ),
                    array(
                        "text" => "Option three text",
                        "value" => "3"
                    )
                )
            )
        );

        $new_form = new Form( $form, $fields );
        $new_form->render_form();
        ?>
    </body>
</html>


