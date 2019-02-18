<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 8/2/2019
 * Time: 1:20 PM
 */

namespace App\Service;


use App\Entity\Core\Email\EmailAbstract;
use App\Entity\Core\Email\MailLog;
use Doctrine\ORM\EntityManagerInterface;

class Mailer {
    private $em;
    private $isDev;
    private $debugEmail;
    private $mailer;
    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->isDev = strtolower(env("APP_ENV")) === "dev";
        $this->debugEmail = env("DEBUG_EMAIL");
    }
    public function send(EmailAbstract $mail) {
        try {
            if ($this->isDev) {
                $sMail = $mail->getSwiftMessage();
                $sMail->setBody("Redirected from: \n".  implode("\n", array_keys($sMail->getTo()))."\n\n".$sMail->getBody());
                $sMail->setBody("CC from: \n".  implode("\n", array_keys($sMail->getCc()))."\n\n".$sMail->getBody());
                $sMail->setTo($this->debugEmail);
            } else {
                $sMail = $mail->getSwiftMessage();
            }
            $this->mailer->send($sMail, $error);
            $error = implode("\n", $error);
        } catch (\Exception $ex) {
            $error = $ex->getMessage()."\n";
            $error .= $ex->getTraceAsString();
        }
        $log = new MailLog();
        $log->setReceivers($mail->getReceivers());
        $log->setBody($mail->getBody());
        $log->setCc($mail->getCc());
        $log->setClassName(get_class($mail));
        $log->setSubject($mail->getSubject());
        if ($error) {
            $log->setErrors($error);
        }
        if ($this->isDev) {
            $log->setRedirect($this->debugEmail);
        }
        $this->em->persist($log);
        $this->em->flush();
    }
}