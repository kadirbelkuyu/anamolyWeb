<?php
// Comment out the above line if not using Composer
require("./sendgrid-php.php");
// If not using Composer, uncomment the above line and
// download sendgrid-php.zip from the latest release here,
// replacing <PATH TO> with the path to the sendgrid-php.php file,
// which is included in the download:
// https://github.com/sendgrid/sendgrid-php/releases

$email = new \SendGrid\Mail\Mail();

// For a detailed description of each of these settings, 
// please see the 
// [documentation](https://sendgrid.com/docs/API_Reference/api_v3.html).
$email->setSubject(
    new \SendGrid\Mail\Subject("Sending with SendGrid is Fun 2")
);

$email->addTo(new \SendGrid\Mail\To("elmer.thomas@sendgrid.com", "Example User"));
$email->addTo(new \SendGrid\Mail\To("elmer.thomas+1@sendgrid.com", "Example User1"));
$toEmails = [ 
    new \SendGrid\Mail\To("elmer.thomas+2@sendgrid.com", "Example User2"),
    new \SendGrid\Mail\To("elmer.thomas+3@sendgrid.com", "Example User3")
];
$email->addTos($toEmails);

$email->addCc(new \SendGrid\Mail\Cc("elmer.thomas+4@sendgrid.com", "Example User4"));
$ccEmails = [ 
    new \SendGrid\Mail\Cc("elmer.thomas+5@sendgrid.com", "Example User5"),
    new \SendGrid\Mail\Cc("elmer.thomas+6@sendgrid.com", "Example User6")
];
$email->addCcs($ccEmails);

$email->addBcc(
    new \SendGrid\Mail\Bcc("elmer.thomas+7@sendgrid.com", "Example User7")
);
$bccEmails = [ 
    new \SendGrid\Mail\Bcc("elmer.thomas+8@sendgrid.com", "Example User8"),
    new \SendGrid\Mail\Bcc("elmer.thomas+9@sendgrid.com", "Example User9")
];
$email->addBccs($bccEmails);

$email->addHeader(new \SendGrid\Mail\Header("X-Test1", "Test1"));
$email->addHeader(new \SendGrid\Mail\Header("X-Test2", "Test2"));
$headers = [
    new \SendGrid\Mail\Header("X-Test3", "Test3"),
    new \SendGrid\Mail\Header("X-Test4", "Test4")
];
$email->addHeaders($headers);

$email->addDynamicTemplateData(
    new \SendGrid\Mail\Substitution("subject1", "Example Subject 1")
);
$email->addDynamicTemplateData(
    new \SendGrid\Mail\Substitution("name", "Example Name 1")
);
$email->addDynamicTemplateData(
    new \SendGrid\Mail\Substitution("city1", "Denver")
);
$substitutions = [
    new \SendGrid\Mail\Substitution("subject2", "Example Subject 2"),
    new \SendGrid\Mail\Substitution("name2", "Example Name 2"),
    new \SendGrid\Mail\Substitution("city2", "Orange")
];
$email->addDynamicTemplateDatas($substitutions);

$email->addCustomArg(new \SendGrid\Mail\CustomArg("marketing1", "false"));
$email->addCustomArg(new \SendGrid\Mail\CustomArg("transactional1", "true"));
$email->addCustomArg(new \SendGrid\Mail\CustomArg("category", "name"));
$customArgs = [
    new \SendGrid\Mail\CustomArg("marketing2", "true"),
    new \SendGrid\Mail\CustomArg("transactional2", "false"),
    new \SendGrid\Mail\CustomArg("category", "name")
];
$email->addCustomArgs($customArgs);

$email->setSendAt(new \SendGrid\Mail\SendAt(1461775051));

// You can add a personalization index or personalization parameter to the above
// methods to add and update multiple personalizations. You can learn more about 
// personalizations [here](https://sendgrid.com/docs/Classroom/Send/v3_Mail_Send/personalizations.html).

// The values below this comment are global to entire message

$email->setFrom(new \SendGrid\Mail\From("elmer.thomas@sendgrid.com", "DX"));

$email->setGlobalSubject(
    new \SendGrid\Mail\Subject("Sending with SendGrid is Fun and Global 2")
);

$plainTextContent = new \SendGrid\Mail\PlainTextContent(
    "and easy to do anywhere, even with PHP"
);
$htmlContent = new \SendGrid\Mail\HtmlContent(
    "<strong>and easy to do anywhere, even with PHP</strong>"
);
$email->addContent($plainTextContent);
$email->addContent($htmlContent);
$contents = [
    new \SendGrid\Mail\Content("text/calendar", "Party Time!!"),
    new \SendGrid\Mail\Content("text/calendar2", "Party Time 2!!")
];
$email->addContents($contents);

$email->setTemplateId(
    new \SendGrid\Mail\TemplateId("d-bff75447850d4a3286f5772f4eb7a3f8")
);

$email->addGlobalHeader(new \SendGrid\Mail\Header("X-Day", "Monday"));
$globalHeaders = [
    new \SendGrid\Mail\Header("X-Month", "January"),
    new \SendGrid\Mail\Header("X-Year", "2017")
];
$email->addGlobalHeaders($globalHeaders);

$email->addCategory(new \SendGrid\Mail\Category("Category 1"));
$categories = [
    new \SendGrid\Mail\Category("Category 2"),
    new \SendGrid\Mail\Category("Category 3")
];
$email->addCategories($categories);

$email->setReplyTo(
    new \SendGrid\Mail\ReplyTo(
        "dx+replyto2@sendgrid.com",
        "DX Team Reply To 2"
    )
);

$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '.  $e->getMessage(). "\n";
}