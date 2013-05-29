<?php

namespace Biruwon\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DashboardBundle:Default:index.html.twig', array('name' => $name));
    }

    public function twitterAction()
    {
        $twitterClient = $this->container->get('guzzle.twitter.client');
        $status = $twitterClient->get('statuses/user_timeline.json')
            ->send()->getBody();

        return $this->render('DashboardBundle:Default:index.html.twig', array(
            'status' => $status
            )
        );
    }

    public function facebookAction()
    {
    	$facebookClient = $this->container->get('guzzle.facebook.client');
    	$status = $facebookClient->get('100003874467772')
    		->send()->getBody();

    	return $this->render('DashboardBundle:Default:index.html.twig', array(
            'status' => $status
    		)
    	);
    }
}
