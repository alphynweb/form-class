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
        $ths = 4;
        $fields = array (
            // Text input one
            array (
                "input" => "text",
                "name" => "text_field_one",
                "id" => "textFieldOne",
                "value" => "Text box one value",
                "label" => array(
                    "text" => "label text"
                ),
                "placeholder" => "Text box placeholder"
            ),
            // Text input two
            array (
                "input" => "email",
                "name" => "email",
                "label" => false,
                "placeholder" => "Please enter your email here"
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
                "value" => "submit button value"
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

        $form = new Form( $fields );
        $html = $form->get_html();
        echo $html;
        ?>
    </body>
</html>


