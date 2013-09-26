<?php

namespace Biruwon\DashboardBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\BinaryFileResponse;
use Guzzle\Http\Client,
    Guzzle\Plugin\Oauth\OauthPlugin;
use Biruwon\DashboardBundle\Form\ProfileType,
    Biruwon\DashboardBundle\Form\UserType,
    Biruwon\DashboardBundle\Entity\User,
    Biruwon\DashboardBundle\Entity\Document,
    Biruwon\DashboardBundle\Entity\Profile;

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
        $em = $this->container->get('doctrine')->getManager();

        $image = $em->getRepository('Document')->find($id);

        if(!$image) {
            throw $this->createNotFoundException(
                'No image found for id '.$id
            );
        }

        $response = new BinaryFileResponse($image);
        $response->headers->set('Content-Type', $image->getMimeType());

        return $reponse;
    }

    public function profileAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $profile = $user->getProfile();

        $form = $this->container->get('form.factory')->create(new ProfileType(), $profile);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->container->get('doctrine')->getManager();

            $user->setProfile($profile);
            $em->persist($user);
            $em->flush();

            return new RedirectResponse($this->container->get('router')->generate(
                'dashboard_profile'
            ));
        }

        return $this->container->get('templating')->renderResponse(
            'DashboardBundle:User:profile.html.twig',
                array(
                    'form' => $form->createView()
                )
        );
    }

    public function registerAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getManager();

        $user = new User();
        $form = $this->container->get('form.factory')->create(new UserType(), $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();

            $em->persist($user);
            $em->flush();

            return new RedirectResponse($this->container->get('router')->generate(
                'dashboard_profile'
            ));
        }

        return $this->container->get('templating')->renderResponse(
            'DashboardBundle:User:registration.html.twig',
                array(
                    'form' => $form->createView()
                )
        );
    }

    public function homeAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        return $this->container->get('templating')->renderResponse(
            'DashboardBundle:Default:home.html.twig',
                array(
                    'user' => $user
                )
        );
    }
}
