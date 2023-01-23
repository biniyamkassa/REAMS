<?php
$message = \Swift_Message::newInstance()
                            ->setSubject("[CMHS-REAMS] Test REAMS")
                            ->setFrom('cmhsirb@gmail.com')
                            ->setTo('redetg@gmail.com')
                            ->setBody(
                                "this is automated message. this is just a test."
                                ,
                                'text/html'
                            );
$transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465,'ssl'))->setUsername('cmhsirb@gmail.com')
							     ->setPassword('oeogpomonomhnkqj');


// Create the Mailer using your created Transport
$mailer = new \Swift_Mailer($transport);

$send = $mailer->send($message);

?>
