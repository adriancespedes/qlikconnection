<?php
namespace App\Controller;

use App\Qlik\QlikCall;
use App\Qlik\QlikTicket;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/dashboards", name="dashboards")
     */
    public function DashboardsAction(Request $request)
    {
            $protocol = 'https';
            $user = $this->getUserIfExists();
            $ticket = $this->retrieveQlikTicketProvidingUser($user);
            $qlikCallEmbedConfig = $this->provideQlikCallEmbedConfig();
                return $this->render('dashboards/dashboards.html.twig', array_merge(array(
                    'protocol' => $protocol,
                    'ticket' => $ticket,
                ), $qlikCallEmbedConfig));
    }


    private function retrieveQlikTicketProvidingUser($user)
    {
        $qlikTicket = new QlikTicket($user);
        try {
            $ticket = $qlikTicket->retrieveQlikTicket();
        } catch (\Exception $exception) {
            throw new \Exception('Authentication Error');
        }
        return $ticket;
    }

    private function getUserIfExists()
    {
        $mayBeUser = $this->getUser();
        if ($mayBeUser === null) {
            return false;
        }
        return $mayBeUser;
    }

    private function provideQlikCallEmbedConfig()
    {
        $qlikCall = new QlikCall();
        return $qlikCall->retrieveParametersCall('PAGE1');
    }
}