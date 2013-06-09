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

        $token = $facebookClient->get(
            '/oauth/access_token?client_id=***&client_secret=***&grant_type=client_credentials')
        ->send()->getBody();

        $access_token = substr($token, 13);

    	$status = $facebookClient->get('100003874467772/picture/call?access_token='.$access_token)
    		->send()->getEffectiveUrl();

    	return $this->render('DashboardBundle:Default:index.html.twig', array(
            'status' => $status
    		)
    	);
    }
}
