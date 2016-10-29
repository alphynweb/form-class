<!DOCTYPE html>
<html>
    <head>
        <?php require_once 'class_form.php'; ?>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
        // Tester - three fields
        $form = array (
            "message" => "This is a sample message for the form",
            "id" => "formId",
            "name" => "formName",
            "method" => "post",
            "querystring" => "t=10&r=20"
        );
        $fields = array (
            // Text input one
            array (
                "input" => "text",
                "name" => "text_field_one",
                "id" => "textFieldOne",
                "value" => "Text box one value",
                "label" => "Text box one label",
                "placeholder" => "Text box placeholder"
            ),
            // Text input two
            array (
                "input" => "email",
                "name" => "email",
                "placeholder" => "Please enter your email here"
            ),
            // Password input
            array (
                "input" => "password",
                "name" => "password"
            ),
            // Button
            array (
                "input" => "button",
                "id" => "button_one_id",
                "value" => "button one value",
                "text" => "button one text"
            ),
            // Submit button
            array (
                "input" => "button",
                "type" => "submit",
                "label" => "Submit button label text",
                "value" => "submit button value"
            ),
            array (
                "input" => "link",
                "text" => "Forgot your password?",
                "href" => "http://www.google.com",
                "class" => "button"
            ),
            array (
                "input" => "select",
                "options" => array (
                    "Option one text" => "1",
                    "Option two text" => "2",
                    "Option three text" => "Value for option three"
                ),
                "label" => ""
            ),
            // Radio button group
            // Name = name of radio button group
            // Buttons = array of buttons. Each button has value, checked, 
            // Example -
            // array(
            // "input" => "radio",
            // "name" => "Name of radio button group",
            // "buttons" => array (
            //      "Value one" => array (
            //                  "checked" => true,
            //                  "disabled" => true
            //                    ),
            //      "Value two" => array (
            //                  "checked" => false,
            //                  "disabled" => false
            //                    )
            //              )
            // )
            array (
                "input" => "radio",
                "name" => "radio button group",
                "buttons" => array (
                    "one" => array (
                        "checked" => true,
                        "disabled" => true
                    ),
                    "two" => array (
                        "checked" => false,
                        "disabled" => false
                    ),
                    "three" => array (),
                    "four" => array (),
                    "five" => array (
                        "checked" => true,
                        "disabled" => false
                    )
                )
            )
                // Text input three
//            array (
//                "type" => "text",
//                "label_text" => "Label text for text input three",
//                "placeholder" => "Text box three placeholder text"
//            ),
                // Email input
//            array (
//                "type" => "email",
//                "name" => "email_one"
//            )
                // Radio group
                // Submit button
//            array (
//                "type" => "submit",
//                "name" => "submit"
//            )
        );

        $new_form = new Form( $form, $fields );
        $new_form->renderForm();
        ?>
    </body>
</html>


