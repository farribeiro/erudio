<?php

namespace SME\IntranetBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use SME\IntranetBundle\Entity\Notification;
use SME\IntranetBundle\Entity\PortalUser;

class NotificationService {
    
    private $orm;
    
    public function __construct (Registry $doctrine) {
        $this->orm = $doctrine;
    }
    
    public function send($text, PortalUser $user, $type = 'common') {
    	$notify = new Notification();
    	$notify->setTexto($text);
    	$notify->setUsuario($user);
    	$notify->setTipo($type);
    	$notify->setLido(false);
    	$notify->setCriadoEm(new \DateTime());
    	try {
            $em = $this->orm->getManager();
            $em->persist($notify);
            $em->flush();
            return true;
    	} catch (\Exception $ex) {
            return false;
    	}
    }
    
    public function getTotalNotificationsNotRead(PortalUser $user)
    {
	$notifications = $this->orm->getRepository('IntranetBundle:Notification')->findBy(array('usuario' => $user, 'lido' => false));
    	return count($notifications);
    }
    
    public function getNotifications(PortalUser $user)
    {    	
    	$notifications = $this->orm->getRepository('IntranetBundle:Notification')->findBy(array('usuario' => $user, 'lido' => false)); 	
    	return $notifications ? $notifications : false;
    }
    
    public function getNotificationsByPage(PortalUser $user, $page)
    {
    	$limit = Notification::LIMIT_PAGES;
    	$firstRegistry = ($page * $limit) - $limit;
    	$notifications = $this->orm->getRepository('IntranetBundle:Notification')->findBy(array('usuario' => $user, 'lido' => false),array('criadoEm'=>'DESC', 'id'=> 'DESC'),$limit,$firstRegistry);
        return $notifications ? $notifications : false;
    }
    
    public function deleteNotification($id) {
    	$notification = $this->orm->getRepository('IntranetBundle:Notification')->findOneById($id);
    	if (!$notification) {
            return false;
    	}
    	$notification->setLido(true);
    	$em = $this->orm->getManager();
    	$em->flush();
    	return true;
    }
    
    public function deleteNotifications($ids) {
    	foreach ($ids as $x => $id) {
            $notification = $this->orm->getRepository('IntranetBundle:Notification')->findOneById($id);
            if (!$notification) {
                return false;
            }
            $notification->setLido(true);
            $em = $this->orm->getManager();
            $em->flush();
    	}
    	return true;
    }
}
