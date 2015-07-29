<?php
namespace Topxia\WebBundle\Listener;
 
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Topxia\Service\Common\ServiceKernel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class KernelResponseListener

{
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
            return ;
        }
    	$request = $event->getRequest();
        $currentUser = $this->getUserService()->getCurrentUser();        
        $setting = $this->getSettingService()->get('login_bind');
        $user_agent = $request->server->get('HTTP_USER_AGENT');
        $_target_path = $request->getPathInfo();

        $auth = $this->getSettingService()->get('auth');
        if ($currentUser->isLogin() && !in_array('ROLE_SUPER_ADMIN', $currentUser['roles']) && $auth['fill_userinfo_after_login']) {

            $whiteList = array('/fill/userinfo','/logout','/register/mobile/check','/register/email/check');
            if (in_array($request->getPathInfo(), $whiteList) or strstr($request->getPathInfo(),'/admin')) {
                return ;
            }

            $isFillUserInfo = $this->checkUserinfoFieldsFill($currentUser);

            if (!$isFillUserInfo) {
                $url = $this->container->get('router')->generate('login_after_fill_userinfo');
                $response = new RedirectResponse($url);
                $event->setResponse($response);
                return ;
            }
        }

        if (strpos($user_agent,'MicroMessenger') && !$currentUser->isLogin() && $setting['enabled'] && $setting['weixinmob_enabled']) {
            $route = 'login_bind';
            $whiteList = array('/login/bind/weixinmob','/login/bind/weixinmob/callback','/login/bind/weixinmob/new','/login/bind/weixinmob/newset','/login/bind/weixinmob/existbind','/register','/partner/login');
            if (in_array($request->getPathInfo(), $whiteList)) {
                return ;
            }
            $url = $this->container->get('router')->generate($route,array('type' => 'weixinmob','_target_path' => $request->getPathInfo()));
            $response = new RedirectResponse($url);
            $event->setResponse($response);
            return ;
        } 

    }

    private function checkUserinfoFieldsFill($user)
    {
        $auth = $this->getSettingService()->get('auth');
        $userProfile = $this->getUserService()->getUserProfile($user['id']);
        $userProfile['email'] = strstr($user['email'],'@edusoho.net') ? '' : $user['email'];

        $isFillUserInfo = true;
        foreach($auth['registerSort'] as $key => $val){
            if (!$userProfile[$val]) {
                $isFillUserInfo = false;
            }
        }

        return $isFillUserInfo;
    }


    protected function getServiceKernel()
    {
        return ServiceKernel::instance();
    }

    protected function getSettingService()
    {
        return $this->getServiceKernel()->createService('System.SettingService');
    }

    protected function getUserService()
    {
        return ServiceKernel::instance()->createService('User.UserService');
    }
}