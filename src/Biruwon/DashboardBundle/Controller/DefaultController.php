<?php

namespace Biruwon\DashboardBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\HttpFoundation\BinaryFileResponse;
use Guzzle\Http\Client,
    Guzzle\Plugin\Oauth\OauthPlugin;

class DefaultController extends ContainerAware
{
    public function twitterAction()
    {
        $twitterClient = $this->container->get('guzzle.twitter.client');
        $status = $twitterClient->get('statuses/user_timeline.json')
            ->send()->getBody();

        return $this->container->get('templating')->renderResponse(
            'DashboardBundle:Default:index.html.twig',
                array(
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

    	return $this->container->get('templating')->renderResponse(
            'DashboardBundle:Default:index.html.twig',
                array(
                    'status' => $status
    		)
    	);
    }

    public function showImageAction($id)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $image = $em->getRepository('Binary')->find($id);

        if(empty($image)) {
            throw new Exception("Image not found");
        }

        $response = new BinaryFileResponse($image);
        $response->headers->set('Content-Type', $image->getMimeType());

        return $reponse;
    }

    public function profileAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $profile = $user->getProfile();

        $form = $this->container->get('form.factory')->create(new ProfileType(), $profile);

        return $this->container->get('templating')->renderResponse(
            'DashboardBundle:Default:profile.html.twig',
                array(
                    'form' => $form->createView()
                )
        );
     }
}
