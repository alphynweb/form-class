<!DOCTYPE html>
<html>
    <head>
        <?php require_once 'class_form.php'; ?>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script>
            function testFunction() {
                console.log("Form submitted");
            }
        </script>

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
            "querystring" => "querystring=test_querystring",
            "onsubmit" => "testFunction"
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
                "label" => "Text box one label",
                "placeholder" => "Required field",
                "required" => "required"
            ),
////            // Text input two
            array (
                "input" => "email",
                "name" => "email",
                "id" => "email",
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
                "text" => "Test text for textarea",
                "required" => "required"
            ),
////            // Button
            array (
                "input" => "submit",
////                "name" => "submit"
            ),
            // Link
            array (
                "input" => "link",
                "text" => "A link",
                "href" => "http://www.google.com"
            ),
            array (
                "input" => "url",
                "placeholder" => "Enter url",
                "label" => "URL goes here"
            ),
            array (
                "input" => "select",
                "options" => array (
                    array (
                        "text" => "Option one text",
                        "value" => "1"
                    ),
                    array (
                        "text" => "Option two text",
                        "value" => "2",
                        "selected" => "selected"
                    ),
                    array (
                        "text" => "Option three text",
                        "value" => "3"
                    )
                )
            ),
            array (
                "input" => "radio",
                "buttons" => array (
                    array (
                        "name" => "Radio group one",
                        "value" => "Radio button one"
                    ),
                    array (
                        "name" => "Radio group one",
                        "value" => "Radio button two",
                        "checked" => "checked"
                    ),
                    array (
                        "name" => "radio group one",
                        "value" => "Radio button three"
                    )
                )
            ),
            array (
                "input" => "number",
                "name" => "number",
                "min" => 1,
                "max" => 10,
                "value" => "89"
            )
        );

        $new_form = new Form( $form, $fields );
        //$new_form->render_form();
        ?>
    </body>
</html>


