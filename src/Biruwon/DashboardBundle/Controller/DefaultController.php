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
    Biruwon\DashboardBundle\Entity\User;

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
    	$status = $facebookClient->get('100003874467772')
    		->send()->getBody();

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

    public function registerAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getEntityManager();

        $user = new User();
        $form = $this->container->get('form.factory')->create(new UserType(), $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();

            $em->persist($user);
            $em->flush();
            die("tes");

            return new RedirectResponse($this->generateUrl('dashboard_home'));
        }

        return $this->container->get('templating')->renderResponse(
            'DashboardBundle:User:registration.html.twig',
                array(
                    'form' => $form->createView()
                )
        );
    }
}
